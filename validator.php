<?php
namespace Validator ;
require_once 'repository.php';
use Repository as Res;

$tabChoix = ["0","1","2","3","4"];

function verifPresent(string $choix,array $tab):bool{
if(array_search($choix,$tab))
    {
        return true;
    }
return false;
} 


function verifUnicite(string $element,array $tableau, string $type):int
{
if(array_search($element, array_column($tableau, $type)) !== false) 
{
    return 20;
}
return 10;
}

function nombre(string $type):int
{
if($type === "tel")
        {
return 9;
        }
if($type === "code")
        {
return 4;
        }
return 0;
}

function verifFormat(string $texte, string $type):int
{
if(strlen($texte) === nombre($type))
        {
return 10;
        }
return 20;
}

function verifPrefix(string $telephone):int
{
$prefixes = ['77','78','76','75','70'];
if(array_search($telephone[0].$telephone[1],$prefixes)!==false)
            {
return 10;   
            }
return 20;
}

function verifChampNonVide(string $champ):int
{
if($champ !== '')
        {
return 10;
        }
return 20;
}

function verifSoldeInitial(int $solde):int
{
if($solde >= 0)
        {
return 10;
        }
return 20;
}

function verifExistence(string $telephone, array $tableau):int
{
if(Res\trouverIndexWalletParTelephone($telephone, $tableau) !== -1)
        {
return 10;
        }
return 20;
}

function verifMontantPositif(int $montant):int
{
if($montant > 0)
        {
return 10;
        }
return 20;
}

function verifSoldeDisponible(int $solde, int $montant, int $frais):int
{
if($solde - $frais >= $montant)
        {
return 10;
        }
return 20;
}