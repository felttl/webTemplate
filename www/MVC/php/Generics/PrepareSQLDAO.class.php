<?php
/** 
 * permet d'eviter de répéter la préparation des requetes dans les autres services
 * notamment les bind_param et autres
 * dans tous les type de requete sql (SELECT, INSERT, DELETE, UPDATE)
 * (prépare tout et execute tout ensuite en 1 fois)
 * 
 * Attention opérations non supportées: 
 *      - la classe ne fais pas les comparaisons (requete mpls complexe avec join ou opérateur différents de '=')
 */
class PrepareSQLDAO{

    // requete en cours (debuggage)
    private $sql; 
    private $connexion;

    protected $specifier;    
    // synchronisation des données
    private $binders;
    private $binderValeurs; 
    // types pour pdo en fonction des types d'entrée (pour bindValue)
    public static $types = [
        "integer" => PDO::PARAM_INT, 
        "boolean" => PDO::PARAM_BOOL,      
        "string" => PDO::PARAM_STR,
        "double" => PDO::PARAM_STR, 
        "float" => PDO::PARAM_STR
    ];

    public function __construct() {
        // différencier les données des variables(préparées) cachées
        $this->specifier = "param";
        $this->connexion=SingleConnexion::getConnexion();
    }


    /**
     * permet d'executer une requete préparée de selection (Générique avec comparateur conditionnel "=")
     * - arg1 nom de la table ou extraire les données 
     * - arg2 liste des paramètres de la requete 
     * avec (clef - valeu) : clef sont les noms des colonnes ou faire le where et valeur pour les chercher
     * - arg3 liste des colonnes a inclure 
     * 
     *  si aucune colonnes sélectionnées tous 
     *  les champs seront sélectionnés avec * dans
     *  la requete préparée
     * 
     *  @return array|bool listes ou matrice si ok, 1 si erreur
     */
    public function execSelect() {
        $res = false;
        $this->sql = "SELECT ";
        // reset des données de preparation
        $this->binders=[];  
        $this->binderValeurs=[];     
        if (func_num_args() > 0 && func_num_args() <= 3){
            $nomTable = func_get_arg(0);
            try {
                if (func_num_args() == 3){
                    // on ajoute les colonnes spécifiques
                    $colonnesAffichés=func_get_arg(2);
                    for ($i=0; $i < sizeof($colonnesAffichés); $i++) {     
                        $this->sql .= $colonnesAffichés[$i];
                        if (sizeof($colonnesAffichés) > 1 && $i < sizeof($colonnesAffichés)-1)
                            $this->sql .= ', '; // si pas d'elem aprés la virgule et non unique                    
                    }  
                    $this->sql .= ' ';   
                } else {
                    $this->sql .= " * ";
                }
                $this->sql .= "FROM $nomTable"; 
                if(func_num_args() >= 2){
                    // on rajoute tous les arguments spécifiques a préparer    
                    $colName = array_keys(func_get_arg(1)); // clefs de la liste associative (clefs-valeurs)
                    $values = func_get_arg(1); // valeurs de la liste associative
                    $this->sql .= " WHERE ";       
                    $this->binderValeurs = []; // clear             
                    for ($i=0; $i < sizeof($colName); $i++) { 
                        // on ajoute : nomColonne = :paramValue
                        $val = $values[$colName[$i]];
                        $this->sql .= $colName[$i]."= :$this->specifier".$i." ";
                        $this->binders[] = ":$this->specifier".$i;
                        $this->binderValeurs[] = $val;
                        if (sizeof($colName) > 1 && $i < sizeof($colName)-1)
                            $this->sql .= ' AND ';
                    } 
                    $requete = $this->connexion->prepare($this->sql);  
                    $this->bindAll($requete);
                    $requete->execute(); // renvoie un booléen
                    $res = $requete->fetchAll();  
                    if (!$requete) {
                        throw new Exception("PDO::errorInfo():".$this->connexion->errorInfo());
                    }             
                } else { // pas de preparation necessaire
                    $res = $this->connexion->query($this->sql)->fetch();
                }
            } catch (PDOException $th){
                echo "PDOException: ".$th->getMessage();                
            } catch (Throwable $th) {
                echo "préparation de l'affichage échoué: ".$th->getMessage();
            }
        } else {
            throw new Error("execSelect(): nombre de paramètre incorrect<br>", 1);        
        }
        return $res;
    }

    /**
     * permet de préparer chaque variables les listes préRemplies
     * pour l'execution finale
     */
    private function bindAll($stmt){
        try {
            // loop pour les binders
            for ($i=0; $i < sizeof($this->binders); $i++) { 
                $val = $this->binderValeurs[$i];
                $valType=gettype($val);
                $stmt->bindParam(
                    $this->binders[$i], 
                    $val, 
                    self::$types[$valType]
                );
            }             
        } catch (\Throwable $th) {
            echo $th->getMessage().$this->connexion->errorInfo()[2];
        }

    }

    /**
     * fonction de debuggage
     */
    public function afficheInfos(){
        echo "<br>------------ infos ------------<br>";
        echo "sql            : ".$this->sql."<br>";        
        echo "binders        : ".print_r($this->binders)."<br>";
        echo "bindersvaleurs : ".print_r($this->binderValeurs)."<br>";        
        echo "<br>-------------------------------<br>";
    }


}

// end page