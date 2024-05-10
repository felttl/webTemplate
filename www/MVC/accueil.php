<?php
// author: felix TTL
// URL: accueil.php?emplacement=...&action=...



$emplacement = $_REQUEST["emplacement"] ?? "accueil";
// a changer pour le mode prod
ExceptionManager::setMode(ExceptionManager::MOD_DEBUG_D); 
ErrorHandlerManager::setMode(ErrorHandlerManager::REPORT_ALL);

switch ($emplacement) {
    case 'value':
        # code...
        break;
    
    default:
        # code...
        break;
}


// end page