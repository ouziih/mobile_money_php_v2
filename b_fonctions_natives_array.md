# Les fonctions natives de tableaux (`array_*`) en PHP

## 1. Pourquoi ces fonctions existent

En Partie A de ce projet, toute manipulation de tableau (chercher un élément, filtrer, transformer) a été écrite **manuellement** avec des boucles (`for`, `foreach`). C'était un choix pédagogique imposé : comprendre ce qui se passe "sous le capot" avant d'utiliser les outils tout faits.

PHP fournit en réalité plus de 80 fonctions natives pour manipuler les tableaux, qui couvrent la quasi-totalité des besoins courants : transformer, filtrer, trier, réduire, rechercher, fusionner. Utiliser ces fonctions natives en Partie B permet d'écrire un code plus court, plus lisible, et généralement plus optimisé (ces fonctions sont implémentées en C dans le moteur PHP, donc plus rapides qu'une boucle PHP équivalente).

---

## 2. `array_map` — transformer chaque élément

Applique une fonction à **chaque élément** d'un tableau, et retourne un nouveau tableau avec les résultats (le tableau d'origine n'est pas modifié).

```php
$soldes = [1000, 2000, 5000];

$soldesAvecFrais = array_map(fn($solde) => $solde * 0.99, $soldes);
// [990, 1980, 4950]
```

### Équivalent manuel (Partie A)

```php
function appliquerFrais(array $soldes): array {
    $resultat = [];
    foreach ($soldes as $solde) {
        $resultat[] = $solde * 0.99;
    }
    return $resultat;
}
```

`array_map` remplace exactement ce schéma : "parcourir, transformer, collecter."

---

## 3. `array_filter` — garder seulement certains éléments

Parcourt un tableau et **ne garde que** les éléments pour lesquels une fonction de test renvoie `true`.

```php
$wallets = [
    ['client' => 'Awa', 'solde' => 5000],
    ['client' => 'Modou', 'solde' => -200],
    ['client' => 'Fatou', 'solde' => 12000],
];

$walletsPositifs = array_filter($wallets, fn($w) => $w['solde'] > 0);
```

### Équivalent manuel (Partie A)

```php
function filtrerSoldesPositifs(array $wallets): array {
    $resultat = [];
    foreach ($wallets as $wallet) {
        if ($wallet['solde'] > 0) {
            $resultat[] = $wallet;
        }
    }
    return $resultat;
}
```

**Point d'attention** : `array_filter` conserve les **clés d'origine** du tableau (donc des "trous" dans la numérotation peuvent apparaître). Si on veut renuméroter à partir de 0, on enchaîne avec `array_values()`.

---

## 4. `array_search` et `in_array` — rechercher un élément

- `in_array($valeur, $tableau)` : renvoie `true`/`false` selon que la valeur existe dans le tableau.
- `array_search($valeur, $tableau)` : renvoie **la clé** (l'index) de la valeur trouvée, ou `false` si absente.

```php
$telephones = ['771001010', '782345678', '781112233'];

in_array('782345678', $telephones);      // true
array_search('782345678', $telephones);  // 1
```

### Équivalent manuel (Partie A — notre propre projet)

```php
function trouverIndexWalletParTelephone(string $telephone, array $tableau): int {
    foreach ($tableau as $index => $wallet) {
        if ($wallet['telephone'] === $telephone) {
            return $index;
        }
    }
    return -1;
}
```

C'est très exactement ce que `array_search` fait nativement — à la différence qu'il faut adapter la recherche pour chercher *dans une clé précise* d'un tableau de tableaux (voir section `array_column` ci-dessous).

---

## 5. `array_column` — extraire une colonne

Très utile pour les tableaux de tableaux associatifs (comme `$wallets`). Extrait la valeur d'une seule clé pour chaque élément.

```php
$telephones = array_column($wallets, 'telephone');
// ['771001010', '782345678', ...]

array_search('782345678', $telephones); // donne l'index du wallet correspondant
```

Combiné à `array_search`, ça remplace directement notre fonction `trouverIndexWalletParTelephone`.

---

## 6. `array_push` et l'opérateur `[]` — ajouter un élément

```php
$tableau = [1, 2, 3];
array_push($tableau, 4);   // [1, 2, 3, 4]

// Équivalent plus idiomatique en PHP :
$tableau[] = 4;            // [1, 2, 3, 4]
```

C'est la fonction qu'on a explicitement interdite en Partie A. Notre fonction manuelle :

```php
function ajoutDansUnTableau(array $element, array &$tableau): void {
    $nouvelIndex = count($tableau);
    $tableau[$nouvelIndex] = $element;
}
```

...reproduit exactement ce que fait `$tableau[] = $element;` nativement.

---

## 7. `usort` — trier avec une logique personnalisée

Trie un tableau selon une fonction de comparaison qu'on fournit.

```php
$wallets = [
    ['client' => 'Awa', 'solde' => 5000],
    ['client' => 'Modou', 'solde' => 12000],
];

usort($wallets, fn($a, $b) => $b['solde'] - $a['solde']); // tri décroissant par solde
```

La fonction de comparaison doit renvoyer :
- un nombre négatif si `$a` doit venir avant `$b`,
- un nombre positif si `$a` doit venir après `$b`,
- `0` si égaux.

---

## 8. `array_reduce` — agréger en une seule valeur

Parcourt le tableau pour produire **une seule valeur finale** (somme, total, concaténation...).

```php
$transactions = [
    ['montant' => 1000], ['montant' => -500], ['montant' => 2000]
];

$totalMouvements = array_reduce(
    $transactions,
    fn($accumulateur, $t) => $accumulateur + $t['montant'],
    0 // valeur de départ
);
// 2500
```

### Équivalent manuel

```php
function calculerTotal(array $transactions): int {
    $total = 0;
    foreach ($transactions as $t) {
        $total += $t['montant'];
    }
    return $total;
}
```

---

## 9. Tableau récapitulatif : Partie A → Partie B

| Besoin métier | Version manuelle (Partie A) | Fonction native (Partie B) |
|---|---|---|
| Vérifier si une valeur existe | boucle + `if` + flag | `in_array()` |
| Trouver l'index d'un élément | boucle + `return $index` | `array_search()` |
| Transformer chaque élément | boucle + tableau résultat | `array_map()` |
| Garder certains éléments | boucle + `if` + tableau résultat | `array_filter()` |
| Ajouter un élément | `$tableau[count($tableau)] = ...` | `array_push()` ou `$tableau[] = ...` |
| Calculer un total | boucle + accumulateur | `array_reduce()` |
| Trier selon un critère | tri manuel (bulle, etc.) | `usort()` |
| Extraire une colonne | boucle + tableau résultat | `array_column()` |

---

## 10. Avantages et limites

**Avantages :**
- Code plus court, plus expressif (on lit l'intention, pas le mécanisme de boucle).
- Moins de risques de bugs (off-by-one, oubli de `return`, boucle infinie).
- Souvent plus performant (implémentation native en C).

**Limites / points de vigilance :**
- Moins explicite pour un débutant : il faut bien connaître la fonction utilisée pour deviner ce qu'elle fait.
- Certaines fonctions ont des comportements surprenants sur les clés (`array_filter` garde les clés d'origine, par exemple).
- Pour des logiques très spécifiques ou complexes, une boucle manuelle peut rester plus lisible qu'un enchaînement de 3-4 fonctions natives.

C'est pour cette raison que la Partie A (apprendre l'algorithmique de base) précède toujours la Partie B (utiliser les outils du langage) dans une formation sérieuse : il faut comprendre ce qu'une fonction native fait *avant* de lui faire confiance.
