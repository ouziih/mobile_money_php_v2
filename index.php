<?php
require_once 'validator.php';
require_once 'controller.php';
$choix = " " ;
function afficheMenu():void{
echo "*** Menu Distributeur ***\n\n";
echo "1 - Créer Wallet\n";
echo "2 - Faire Dépôt\n";
echo "3 - Faire Retrait\n";
echo "4 - Lister les Transactions\n";
echo "0 - Quitter\n\n";
}
function saisieChoix():string{
return readline("Choisissez le numéro correspondant a ce que vous voullez effectuer: ");
}
function reposeChoix():void{
echo "le choix doit est compris entre 0 - 4\n";
}
do
{
afficheMenu();
$choix = saisieChoix();
if(!verifPresent($choix,$tabChoix))
{
reposeChoix();
}
else
{
aFaire($choix, $wallets, $transactions);
}
}
while($choix!=="0")
?>