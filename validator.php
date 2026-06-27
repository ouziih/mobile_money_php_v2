<?php
require_once 'repository.php';
$tabChoix = ["0","1","2","3","4"];

function verifPresent(string $choix,array $tab):bool{
for ($index=0; $index < count($tab); $index++) { 
if($choix===$tab[$index])
        {
return true;
        }
    }
return false;
} 

function decideur(string $mot):string
{
if($mot === "tel")
        {
return 'telephone';
        }
if($mot === "code")
        {
return 'code';
        }
return '';
}

function verifUnicite(string $element,array $tableau, string $type):int
{
$suite = decideur($type);
foreach ($tableau as $e => $value) {
if($value[$suite] === $element)
        {
return 20;
        }
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
foreach ($prefixes as $prefixe => $value) {
if($value === $telephone[0].$telephone[1])
            {
return 10;
            }
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
if(trouverIndexWalletParTelephone($telephone, $tableau) !== -1)
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