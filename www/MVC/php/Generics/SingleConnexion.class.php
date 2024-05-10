
<?php

/**
 * Utilisation du Singleton connexion a la base de données
 *
 * @author Armelle M
 */
class SingleConnexion {
    private static $_instance = null;
    private static $laConnexion;
    private function __construct() {
        try {
            self::$laConnexion = new PDO(
                'mysql:host=localhost;dbname=dbWebTemplateName',
                'root',
                'root'
            );
            // PDO ne gère pas les exceptions c'est notre classe qui le fait (ExceptionManager)
            self::$laConnexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        } catch (PDOException $th){
            $possiblites = [
                1045 => "le mot de passe ou l'identifiant de la base de données n'est pas correct ",
                1049 => "nom de la base de données introuvable ",
                2002 => "le SGBD est incorrect",
                0 => "driver PDO manquant" // erreur fatale qui ne peut normalement pas être capturée (car trop grave)
            ];
            $msg = $possiblites[$th->getCode()];
            if(isset($msg)){
                ExceptionManager::formatMessage($th,$msg,$th->getCode(),false); 
            } else {
                // trés grave car l'erreur n'est pas connu et ne peut pas être traité + pb securité
                throw new InternalException("PDO: erreur non répertoriée");                
            }
        } catch (Throwable $th) {
            ExceptionManager::formatMessage($th,"pb de connexion : ");
            die(); // sécurité (erreur fatale)
        }
    }
    public static function getConnexion(){
        if (is_null(self::$_instance))
            self::$_instance=new SingleConnexion();
        return self::$laConnexion;
    }
    public static function liberer() {
        self::$_instance = null;
        self::$laConnexion = null;
    }
}
