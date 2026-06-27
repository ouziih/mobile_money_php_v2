<?php
require_once 'validator.php';
require_once 'services.php';

function saisirWallet():array{
$wallet=['client'=>'','telephone'=>'','code'=>0,'solde'=>0];
$wallet['client'] =readline("Veuillez saisir un client :");
$wallet['telephone'] =readline("Veuillez saisir un telephone :");
$wallet['code'] = readline("Veuillez saisir un code :");
$wallet['solde']= (int)readline("veuillez saisir un solde");
return $wallet ;
}

function aFaire(string $choix):void
{
global $wallets;
switch ($choix) {
case '1':
if(creerWallet(saisirWallet()) === 10)
            {
echo "Creation reussie\n";
afficherWallet($wallets[count($wallets)-1]);
            }
else{echo "Donnee invalide\n";}
break;
case '2':
$telephone = readline("Telephone du wallet : ");
$montant = (int) readline("Montant a deposer : ");
if(faireDepot($telephone, $montant) === 10)
            {
echo "Depot reussi\n";
            }
else{echo "Depot impossible\n";}
break;
default:
echo "a faire\n";
break;
}
}