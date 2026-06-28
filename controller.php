<?php
namespace Controller;
require_once 'services.php';
require_once 'repository.php';
use Repository as Res;
use Services as Ser;

function saisirWallet():array{
$wallet=['client'=>'','telephone'=>'','code'=>0,'solde'=>0];
$wallet['client'] =readline("Veuillez saisir un client :");
$wallet['telephone'] =readline("Veuillez saisir un telephone :");
$wallet['code'] = readline("Veuillez saisir un code :");
$wallet['solde']= (int)readline("veuillez saisir un solde");
return $wallet ;
}

function aFaire(string $choix, array $wallets, array $transactions):void
{
switch ($choix) {
case '1':
if(Ser\creerWallet(saisirWallet()) === 10)
            {
echo "Creation reussie\n";
Res\afficherWallet($wallets[count($wallets)-1]);
            }
else{echo "Donnee invalide\n";}
break;
case '2':
$telephone = readline("Telephone du wallet : ");
$montant = (int) readline("Montant a deposer : ");
if(Ser\faireDepot($telephone, $montant) === 10)
            {
echo "Depot reussi\n";
            }
else{echo "Depot impossible\n";}
break;
case '3':
$telephone = readline("Telephone du wallet : ");
$montant = (int) readline("Montant a retirer : ");
if(Ser\faireRetrait($telephone, $montant) === 10)
            {
echo "Retrait reussi\n";
            }
else{echo "Retrait impossible\n";}
break;
case '4':
$telephone = readline("Telephone (laisser vide pour tout afficher) : ");
if($telephone === '')
            {
echo "erreur \n";
            }
else{Ser\listerTransactions($transactions, Res\trouverIndexWalletParTelephone($telephone, $wallets));}
break;
default:
echo "a faire\n";
break;
}
}