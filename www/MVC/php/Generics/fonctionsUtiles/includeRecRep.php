<?php

/**
 * permet d'include tous les fichier récursivement d'un répertoire
 * @param string $filepath chemin d'accésdu fichier (relatif ou absolu)
 * @param bool $display mode affichage répertoire
 * @return bool true tout s'est bien passsé false une erreur s'est produite
 */
function includeRep($filepath,$display=true) {
    $res = false;
    $arr = scandir($filepath);
    for ($i=0; $i < sizeof($arr); $i++) { 
        // évite d'utiliser un regex pour le scan les déplacement de repertoires
        if(strcmp("-".$arr[$i]."-", "-.-") && strcmp("-".$arr[$i]."-", "-..-")){
            if($display)
                echo $filepath."/".$arr[$i]."<br>";
            // si on trouve un repertoire on recommence réccursivement
            if(is_dir($arr[$i]))
                includeOnceAll($arr[$i]);
            else { // fichier on inclu une fois
                $cheminFichier = $filepath . '/' . $arr[$i];
                echo $cheminFichier."<br>";
            }
        }
        
    }
    return $res;
}