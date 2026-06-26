<?php
require_once 'repository.php';
$tabChoix = ["0","1","2","3","4"];

function verifPresent(string $choix,array $tab):bool{
    for ($i=0; $i < count($tab); $i++) { 
        if($choix===$tab[$i])
        {
            return true;
        }
    }
    return false;
} 


?>