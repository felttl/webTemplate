<?php

/**
 * interface pour classes de prépération Générique DAO 
 * (exemple: PrepareSQLDAO)
 */
interface RecupInfos{

    /**
     * récupère les informations dans une liste associative (colonne => valeur)
     * @return array informations
     */
    public function getInfoDansListe();

}


// end