<?php


/* base de données de Personne :

    CREATE TABLE `personnes` (
    `codepersonne` int(11) NOT NULL,
    `nom` varchar(20) DEFAULT NULL,
    `prenom` varchar(20) DEFAULT NULL,
    `mail` varchar(30) NOT NULL,
    `mdp` varchar(100) NOT NULL,
    `statut` varchar(30) NOT NULL
    )
 */

/**
 * gestion des droits et connexion
 * vérification des informations
 */
class Authentification {

    /**
     * cherche l'utilisateur dans la base de données pour le connecter
     * @return bool true si personne trouvé et connéctée false sinon
     */
    public static function authentifier($email, $motDePasse) {
        $res = false;        
        try {
            $personneDAO = new PersonneDAO();
            $personne = $personneDAO->getPersonne($email, $motDePasse);         
            if ($personne != false) {
                Session::ouvrirSession($personne);
                $res = true;
            }            
        } catch (Throwable $th) {
            $res = false;
            echo "erreur d'".get_class().": ".$th->getMessage();
        } finally {
            return $res;
        }
    }

}

// end page