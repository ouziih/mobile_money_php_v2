<?php
require_once 'validator.php';
require_once 'repository.php';

function creerWallet(array $wallet):int
{
global $wallets;
if(verifChampNonVide($wallet['client']) === 10 &&
verifFormat($wallet['telephone'], "tel") === 10 &&
verifPrefix($wallet['telephone']) === 10 && 
verifUnicite($wallet['telephone'],$wallets,"tel") === 10 &&
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