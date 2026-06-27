<?php

$wallets=[
    0=>['client'=>'Baila Wane','telephone'=>'771001010','code'=>"1234",'solde'=>0],
    1=>['client'=>'Hawa Baila Wane','telephone'=>'782345678','code'=>"1235",'solde'=>10000]
];

function ajoutDansUnTableau(array $element,array &$tableau):void
{
    $nouvelIndex = count($tableau);
    $tableau[$nouvelIndex] = $element;

}


function afficherWallet(array $wallet):void
{
echo "Client : {$wallet['client']}\n";
echo "Telephone : {$wallet['telephone']}\n";
echo "Code : {$wallet['code']}\n";
echo "Solde : {$wallet['solde']}\n";
}


