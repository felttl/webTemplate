<?php


/* sql :
CREATE TABLE `personnes` (
  `codepersonne` int(11) NOT NULL,
  `nom` varchar(20) DEFAULT NULL,
  `prenom` varchar(20) DEFAULT NULL,
  `mail` varchar(30) NOT NULL,
  `mdp` varchar(100) NOT NULL,
  `statut` varchar(30) NOT NULL (admin ou non)
 */

/**
 * classe de regrouppement des données utilisateur
 */
class Personne implements RecupInfos{

    private $mail; // str   
    private $mdp; // str    
    private $id; // codepersonne == id (unsigned int) 
    private $statut; //int             
    private $nom; // str
    private $prenom; //String
    private $dateInscription; //DateTime    
    private $dateNaissance; //DateTime

    /**
     * constructeur de Personne (9 overload)
     * - arg1 str mail {OBLIGATOIRE}
     * - arg2 str mot de passe ("mdp") {OBLIGATOIRE}
     * - arg3 int id ("codepersonne") {OBLIGATOIRE}
     * - arg4 int statut {OBLIGATOIRE}
     * - arg5 str nom 
     * - arg6 str prenom 
     * - arg7 DateTime dateInscription
     * - arg8 DateTime dateNaissance
     * 
     * (obligatoires a cause de la base de données,
     * mais uniquement pour selectionner des données)
     */
    public function __construct() {
        if(func_num_args() <= 9 && func_num_args() > 0){
            try {
                if(func_num_args() >= 1){
                    if (gettype(func_get_arg(0)) == "string"){
                        $this->mail = func_get_arg(0);
                    } else {
                        throw new Error("paramètre 1 (mail): type incorrect(".gettype(func_get_arg(0)).") string attendu");
                    }
                }
                if(func_num_args() >= 2){
                    if (gettype(func_get_arg(1)) == "string"){
                        $this->mdp = func_get_arg(1);
                    } else {
                        throw new Error("paramètre 2 (mdp): type incorrect(".gettype(func_get_arg(1)).") string attendu");
                    }                    
                }
                if(func_num_args() >= 3){
                    if (gettype(func_get_arg(2)) == "integer"){
                        $this->id = func_get_arg(2);
                    } else {
                        throw new Error("paramètre 3 (codepersonne): type incorrect(".gettype(func_get_arg(2)).") integer attendu");
                    }                    
                }                 
                if(func_num_args() >= 4){
                    if (gettype(func_get_arg(3)) == "integer"){
                        $this->statut = func_get_arg(3);
                    } else {
                        throw new Error("paramètre 4 (statut): type incorrect(".gettype(func_get_arg(3)).") integer attendu");
                    }                    
                } 
                if(func_num_args() >= 5){
                    if (gettype(func_get_arg(4)) == "string"){
                        $this->nom = func_get_arg(4);
                    } else {
                        throw new Error("paramètre 5 (nom): type incorrect(".gettype(func_get_arg(4)).") string attendu");
                    }                    
                }   
                if(func_num_args() >= 6){
                    if (gettype(func_get_arg(5)) == "string"){
                        $this->prenom = func_get_arg(5);
                    } else {
                        throw new Error("paramètre 6 (prenom): type incorrect(".gettype(func_get_arg(5)).") string attendu");
                    }                    
                } 
                if(func_num_args() >= 7){
                    if (gettype(func_get_arg(6)) == "string"){
                        $this->dateInscription = new DateTime(func_get_arg(6));
                    } else {
                        throw new Error("paramètre 7 (dateInscription): type incorrect(".gettype(func_get_arg(6)).") string attendu");
                    }                    
                } 
                if(func_num_args() == 8){
                    if (gettype(func_get_arg(7)) == "string"){
                        $this->dateNaissance = new DateTime(func_get_arg(7));
                    } else {
                        throw new Error("paramètre 8 (dateNaissance): type incorrect(".gettype(func_get_arg(7)).") string attendu");
                    }                    
                }                                                 
            } catch (\Throwable $th) {
                echo $th."<br>erreur du constructeur<br>";
            }
        } else {
            throw new Error("nombre de paramètres invalide");
        }
    }

    // on doit tout voir d'un coup car PRIMORDIAL dans les transitions des données 
    public function getMail(){return $this->mail;}
    public function getMdp(){return $this->mdp;}    
    public function getNom(){return $this->nom;}
    public function getPrenom(){return $this->prenom;}
    public function getStatut(){return $this->statut;}
    public function getId(){return $this->id;}
    public function getDateInscription(){return $this->dateInscription;}    
    public function getDateNaissance(){return $this->dateNaissance;}

    /**
     * récupère les informations dans une liste associative (colonne => valeur)
     * @return array informations
     */
    public function getInfoDansListe(){
        return [
            "mail" => $this->mail,
            "mdp" => $this->mdp,
            "nom" => $this->nom,
            "prenom" => $this->prenom,
            "statut" => $this->statut,
            "id" => $this->id,
            "dateInscription" => $this->dateInscription,
            "dateNaissance" => $this->dateNaissance
        ];
    }

    
}


// end