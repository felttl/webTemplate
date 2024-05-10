<?php

class OutilsDAO{


    /**
     * @return string la requete pour avoir le temps actuel
     */
    public static function getNOWstrSQL(){
        return 'SELECT DATE_FORMAT(NOW(), "%d/%m/%Y %H:%i:%s")';
    }

    /**
     * execute la requete pour savoir quand on est 
     * @param PDO $connexion a la base de données nécessaire
     * @return string|bool renvoie la chaine de la date actuelle au format européen et false si erreur
     */
    public static function getNOWSQL($connexion){
        $res = false;
        try {
            $stmt = $connexion->query(self::getNOWstrSQL());
            if(!$stmt){
                $res=false;
                throw new InternalException("erreur de requete: ".$connexion->errorInfo()[2]);
            }
            $res = $stmt->fetch()[0];
        } catch (\Throwable $th) {
            $res=false;
            ExceptionManager::formatMessage($th);
        } finally {
            return $res;
        }
    }
}


// end page
