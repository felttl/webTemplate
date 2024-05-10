<?php

class OutilsDAO{


    /**
     * @return string la requete pour avoir le temps actuel
     */
    public static function getSQLNOW(){
        return 'SELECT DATE_FORMAT(NOW(), "%d/%m/%Y %H:%i:%s")';
    }
}


// end page
