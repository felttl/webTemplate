<?php

/**
 * interface de préparation de requete 
 * modèle générique (pour plusieurs classes implémentées par RecupInfos)
 * (pour les versions suivantes avec plus de performances)
 */
interface PrepareTemplate extends RecupInfos{
    // a implémenter absolument : (variables privées)
    // private $sql; 
    // private $specifier;
    // private $connexion;
    // private $binders; 
    // private $binderValeurs; 

    // méthode privée pour éviter de répéter du code dans toues les fonctions "exec"
    // private function bindAll(); // sert a préparer toutes les variables

    // méthodes de préparation génériques
    public function execSelect();
    public function execDelete();    
    public function execInsert();
    public function execUpdate();    


    /**
     * afficher les informations (requet, variables, etc..)
     * en cours pour le mode de debuggage
     */
    public function afficheInfos();

}
