<?php


class PersonneDAO{
    const REQUEST_NOW_SQL = 'SELECT DATE_FORMAT(NOW(), "%d/%m/%Y %H:%i:%s")';
    const DATE_PATTERN_SQL = '%d/%m/%Y %H:%i:%s';
    // attention le local n'est pas pris en compte en france (UTC+...) est variable en php!
    const DATE_PATTERN_PHP = "d/m/Y H:i:s"; 
    private $connexion;

    public function __construct(){
        $this->connexion = SingleConnexion::getConnexion();
    }

    public function getPersonneParEmail($email){
        $res = "No user";
        $stmt = new PrepareSQLDAO();
        $output = $stmt->execSelect("personnes", ["mail" => $email]);
        if ($output != null) {
            $res = new Personne(
                (string)$output["mail"],
                (string)$output["mdp"],  
                (int)$output["codepersonne"], 
                (int)$output["statut"],                                      
                (string)$output["nom"],                    
                (string)$output["prenom"]     
            );
        } else 
            $res = false;
        return $res;
    }

    public function getPersonneParID($id) {
        $res = new PrepareSQLDAO();
        $output = $res->execSelect("personnes",["codepersonne" => $id]);
        if ($output != false) {
            $res = new Personne(
                (string)$output["mail"],
                (string)$output["mdp"],  
                (int)$output["codepersonne"], 
                (int)$output["statut"],                                      
                (string)$output["nom"],                    
                (string)$output["prenom"],
                (string)$output["dateInscription"]                                   
            );
        } else
            $res = false;
        return $res;
    }

    /**
     * récupére la personne par mail et mot de passe
     * @param string $email mail de la personne
     * @param string $mdp mot de passe personne
     * @return bool|Personne false si erreur Personne si données trouvées
     */
    public function getPersonne($email, $mdp){
        $stmt = new PrepareSQLDAO();
        $personne = $stmt->execSelect(
            "personnes", 
            ["mail" => $email, "mdp" => $mdp]
        );
        if ($personne != false){
            $personne = $personne[0];
            $res = new Personne(
                (string)$personne["mail"],
                (string)$personne["mdp"],  
                (int)$personne["codepersonne"], 
                (int)$personne["statut"],                                      
                (string)$personne["nom"],                    
                (string)$personne["prenom"]   
            );
        } else {
            $res = false;            
        }
        return $res;
    }

    /**
     * @return bool true si tout est ok false sinon
     */
    public function insererPersonne($nom, $prenom, $email, $mdp, $statut) {
        $res = false;
        $sql = "INSERT INTO `personnes` (nom, prenom, mail, mdp, statut, dateInscription)
                VALUES (:nom, :prenom, :email, :mdp, :statut, :dateInscription)";
        try {
            $stmt = $this->connexion->prepare($sql);
            $stmt->bindParam(":nom", $nom);
            $stmt->bindParam(":prenom", $prenom);   
            $stmt->bindParam(":email", $email);               
            $stmt->bindParam(":mdp", $mdp);     
            $stmt->bindParam(":statut", $statut);
            $stmt->bindParam(":dateInscription", self::REQUEST_NOW_SQL);                 
            $stmt->execute();
            $res = (bool)$stmt->fetch()[0];       
        } catch (PDOException $th){
            
        } catch (Throwable $th) {
            $res=false;
            echo "erreur de requete :".$th->getMessage();
        }
        return $res;
    }
    /**
     * suppression de compte
     * - requière l'accord de l'utilisateur si suppression 
     *  de son compte (confirmation peut être necesssaire)
     * @param Personne|array supprime la personne si c'est l'objet ou si c'est un array de données
     * @return bool true si correctement effectué false sinon
     * @warning ATTENTION id = codepersonne !!!
     */
    public function supprime1Personne($id){
        $sql = "DELETE FROM personnes WHERE codepersonne = :id";    
        $res = false;    
        try {
            $stmt = $this->connexion->prepare($sql);
            if(!$stmt)
                throw new Exception($this->connexion->errorInfo()[2]); 
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $res = (bool)$stmt->fetch()[0];
        } catch (\Throwable $th) {
            $res = false;
            echo "erreur de requete : ".$th->getMessage();
        }
        return $res;
    }


    /**
     * permet de récupérer le nombre de personnes inscrites 
     * (ne requert pas d'interaction utilisateur donc pas préparé)
     * @param int $codeS code de la salle ou chercher le nombre de personnes
     * @return int si on a trouvé le nombre on le renvoie sinon on renvoie -1
     */
    public function nbPersTotalInscrites(){
        $sql = "SELECT COUNT(codepersonne) nbPersTTL FROM personnes";
        return $this->connexion->query($sql)->fetch()[0] ?? -1;
    }

    /**
     * renvoie une matrice de deux éléments 
     * (1 colonne + 1 pour les indexes du tableau dynamique)
     * @return array|bool la liste des dates des personnes inscrites si ok sinon false
     * @notice pas de modification de requete donc pas de preparation
     */
    public function getPersDateInsc(){
        $sql = "SELECT date_format(dateInscription ,".self::DATE_PATTERN_SQL.")".
        " dateInscription FROM personnes";
        $res = $this->connexion->query($sql)->fetchAll();
        return $res;
    }
}