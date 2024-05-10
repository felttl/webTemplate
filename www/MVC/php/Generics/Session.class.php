<?php
/**
 * assurer le suivi des diverses actions réalisées par les 
 * utilisateurs sur l'application
 */
class Session{
    private static $connexion;
    private static $statut = null;
    /**
     * @param Personne $personne permet d'ouvrir une session avec une personne
     */
    public static function ouvrirSession($personne) {
        self::$connexion = SingleConnexion::getConnexion();
        try {
            if(is_null(self::$statut)){
                // pas besoin de requete préparée ici (ce n'est pas l'utilisateur qui peut saisir cette info)
                $tmpRq = self::$connexion->query(
                    "SELECT * FROM access"
                )->fetchAll();
                // récupération des noms des états associés aux codes
                for ($i=0; $i < sizeof($tmpRq); $i++) {
                    self::$statut[$tmpRq[$i]["code"]] = $tmpRq[$i]["description"];   
                }                
            }
            $_SESSION['codepersonne'] = $personne->getId();
            $_SESSION['nom'] = $personne->getNom();
            $_SESSION['prenom'] = $personne->getPrenom();
            $_SESSION['mail'] = $personne->getMail();
            $_SESSION['statut'] = $personne->getStatut();
            $_SESSION['dateInscription'] = $personne->getDateInscription();
            $_SESSION["connecte"] = true;            
        } catch (\Throwable $th) {
            $_SESSION["connecte"] = false; 
            echo "impossible d'ouvrir une session : ".$th->getMessage();
        }

    }

    /**
     * @param string &$msg message a modifier pour la deconnexion
     * @return bool true ok, false erreur
     */
    public static function fermerSessionEtDeconnecter(&$msg=null) {
        $ok = false;
        try {
            SingleConnexion::liberer();       
            $_SESSION["connecte"] = false;                 
            $ok = session_destroy();
            if(!$ok)
                throw new InternalException("déconnexion impossible le destructeur de session ne fonctionne pas");
            $ok = true;
            $msg = "deconnecté avec succés";
        } catch (\Throwable $th) {
            $ok = false;
            $msg = "erreur: déconnexion impossible (".$th->getMessage().")"; 
        } finally {
            return $ok;
        }
        
    }

    public static function getStatutName(){
        return self::$statut[$_SESSION["statut"] ?? 0];
    }

    /**
     * @return bool true connecte ou false sinon
     */
    public static function estConnecte() {
        return $_SESSION["connecte"] ?? false;
    }

    // get user
    public static function getPrenom(){
        return $_SESSION["prenom"];
    }
    public static function getNom(){
        return $_SESSION["nom"];
    }    
    public static function getCodepersonne(){
        return $_SESSION["id"];
    } 
    public static function getMail(){
        return $_SESSION["mail"];
    } 
    public static function getStatut(){
        return $_SESSION["statut"] ?? 0;
    }  
    public static function getDateInscription(){
        return $_SESSION["dateInscription"];
    }                   
}
