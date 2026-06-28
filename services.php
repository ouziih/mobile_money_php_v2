<?php
namespace Services;
require_once 'validator.php';
require_once 'repository.php';
use Repository as Res;
use Validator as Val;
function creerWallet(array $wallet):int
{
    global $wallets;
    if(Val\verifChampNonVide($wallet['client']) === 10 &&
        Val\verifFormat($wallet['telephone'], "tel") === 10 &&
        Val\verifPrefix($wallet['telephone']) === 10 && 
        Val\verifUnicite($wallet['telephone'],$wallets,"telephone") === 10 &&
        Val\verifFormat($wallet['code'], "code") === 10 &&
        Val\verifUnicite($wallet['code'],$wallets,"code") === 10 &&
        Val\verifSoldeInitial($wallet['solde']) === 10
    )
    {
        Res\ajoutDansUnTableau($wallet,$wallets);
        return 10;
    }
return 20;
}

function faireDepot(string $telephone, int $montant):int
{
global $wallets, $transactions;
if(Val\verifExistence($telephone, $wallets) === 10 &&
Val\verifMontantPositif($montant) === 10
       )
       {
$index = Res\trouverIndexWalletParTelephone($telephone, $wallets);
$nouveauSolde = $wallets[$index]['solde'] + $montant;
Res\mettreAJourSolde($index, $nouveauSolde, $wallets);
Res\ajoutDansUnTableau(['montant'=>$montant,'indexClient'=>$index,'frais'=>0], $transactions);
return 10;
       }
return 20;
}

function calculerFrais(int $montant):int
{
if($montant <= 10000)
        {
return 200;
        }
if($montant <= 100000)
        {
return 500;
        }
$frais = intdiv($montant, 100);
if($frais > 5000)
        {
return 5000;
        }
return $frais;
}

function faireRetrait(string $telephone, int $montant):int
{
global $wallets, $transactions;
$index = Res\trouverIndexWalletParTelephone($telephone, $wallets);
$frais = calculerFrais($montant);
if(Val\verifExistence($telephone, $wallets) === 10 &&
    Val\verifMontantPositif($montant) === 10 &&
    Val\verifSoldeDisponible($wallets[$index]['solde'], $montant, $frais) === 10
       )
       {
$nouveauSolde = $wallets[$index]['solde'] - $montant - $frais;
Res\mettreAJourSolde($index, $nouveauSolde, $wallets);
Res\ajoutDansUnTableau(['montant'=>-$montant,'indexClient'=>$index,'frais'=>$frais], $transactions);
return 10;
       }
return 20;
}

function listerTransactions(array $transactions, int $indexFiltre):void
{
foreach ($transactions as $transaction) {
if($transaction['indexClient'] === $indexFiltre)
        {
Res\afficherTransaction($transaction);
echo "---\n";
        }
    }
}

