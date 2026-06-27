<?php
require_once 'validator.php';
require_once 'repository.php';

function creerWallet(array $wallet):int
{
global $wallets;
if(verifChampNonVide($wallet['client']) === 10 &&
verifFormat($wallet['telephone'], "tel") === 10 &&
verifPrefix($wallet['telephone']) === 10 && 
verifUnicite($wallet['telephone'],$wallets,"telephone") === 10 &&
verifFormat($wallet['code'], "code") === 10 &&
verifUnicite($wallet['code'],$wallets,"code") === 10 &&
verifSoldeInitial($wallet['solde']) === 10
       )
       {
ajoutDansUnTableau($wallet,$wallets);
return 10;
       }
return 20;
}

function faireDepot(string $telephone, int $montant):int
{
global $wallets, $transactions;
if(verifExistence($telephone, $wallets) === 10 &&
verifMontantPositif($montant) === 10
       )
       {
$index = trouverIndexWalletParTelephone($telephone, $wallets);
$nouveauSolde = $wallets[$index]['solde'] + $montant;
mettreAJourSolde($index, $nouveauSolde, $wallets);
ajoutDansUnTableau(['montant'=>$montant,'indexClient'=>$index,'frais'=>0], $transactions);
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
$index = trouverIndexWalletParTelephone($telephone, $wallets);
$frais = calculerFrais($montant);
if(verifExistence($telephone, $wallets) === 10 &&
verifMontantPositif($montant) === 10 &&
verifSoldeDisponible($wallets[$index]['solde'], $montant, $frais) === 10
       )
       {
$nouveauSolde = $wallets[$index]['solde'] - $montant - $frais;
mettreAJourSolde($index, $nouveauSolde, $wallets);
ajoutDansUnTableau(['montant'=>-$montant,'indexClient'=>$index,'frais'=>$frais], $transactions);
return 10;
       }
return 20;
}

function listerTransactions(array $transactions, int $indexFiltre):void
{
foreach ($transactions as $transaction) {
if($transaction['indexClient'] === $indexFiltre)
        {
afficherTransaction($transaction);
echo "---\n";
        }
    }
}

