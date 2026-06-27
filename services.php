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
?>