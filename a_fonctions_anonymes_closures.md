# Les fonctions anonymes, fonctions fléchées et closures en PHP

## 1. Qu'est-ce qu'une fonction anonyme ?

Une **fonction anonyme** est une fonction qui n'a pas de nom déclaré. Contrairement aux fonctions classiques (`function maFonction() {}`), elle est créée à la volée et stockée dans une variable, ou passée directement comme argument à une autre fonction.

```php
$direBonjour = function ($nom) {
    return "Bonjour $nom !";
};

echo $direBonjour("Awa"); // Bonjour Awa !
```

**Pourquoi c'est utile ?** Beaucoup de fonctions natives de PHP (notamment les fonctions de tableaux comme `array_map`, `array_filter`, `usort`) attendent une fonction *en paramètre*. Plutôt que de déclarer une fonction nommée juste pour cet usage ponctuel, on écrit directement le traitement sur place.

```php
$nombres = [1, 2, 3, 4, 5];

$carres = array_map(function ($n) {
    return $n * $n;
}, $nombres);

// $carres = [1, 4, 9, 16, 25]
```

---

## 2. Les fonctions fléchées (Arrow functions)

Introduites en **PHP 7.4**, les fonctions fléchées (`fn`) sont une écriture raccourcie des fonctions anonymes, pour les cas simples.

### Syntaxe comparée

```php
// Fonction anonyme classique
$carre = function ($n) {
    return $n * $n;
};

// Fonction fléchée équivalente
$carre = fn($n) => $n * $n;
```

### Règles de la syntaxe `fn`

- Pas de mot-clé `return` : l'expression après `=>` est automatiquement renvoyée.
- Pas d'accolades `{ }` : le corps est limité à **une seule expression**.
- Le mot-clé `fn` remplace `function`.

```php
$nombres = [1, 2, 3, 4, 5];
$carres = array_map(fn($n) => $n * $n, $nombres);
```

### Pourquoi PHP a ajouté ce raccourci ?

Pour les traitements courts (une ligne), la syntaxe `function() { return ...; }` est verbeuse. Les fonctions fléchées rendent le code plus lisible quand la logique est simple — exactement le genre de cas qu'on rencontre tout le temps avec `array_map`, `array_filter`, `usort`.

| Critère | Fonction anonyme | Fonction fléchée |
|---|---|---|
| Mot-clé | `function` | `fn` |
| Corps | Bloc `{ }`, plusieurs lignes possibles | Une seule expression |
| `return` explicite | Obligatoire | Implicite (automatique) |
| Capture des variables externes | Manuelle (`use`) | **Automatique** |

---

## 3. Les Closures : le concept clé derrière les deux

En PHP, une fonction anonyme **est** en réalité une instance d'une classe interne appelée `Closure`. Le mot "closure" (fermeture, en français) désigne la capacité d'une fonction à **capturer et mémoriser des variables de son environnement extérieur**, même après que cet environnement a normalement cessé d'exister.

### Le problème sans closure

```php
function creerMultiplicateur($facteur) {
    function multiplier($n) {
        return $n * $facteur; // ERREUR : $facteur n'est pas visible ici !
    }
    return 'multiplier';
}
```

Comme on l'a vu pour les variables globales, une fonction ne voit pas automatiquement les variables de la fonction qui l'entoure.

### La solution : le mot-clé `use`

```php
function creerMultiplicateur($facteur) {
    return function ($n) use ($facteur) {
        return $n * $facteur;
    };
}

$doubler = creerMultiplicateur(2);
$tripler = creerMultiplicateur(3);

echo $doubler(5); // 10
echo $tripler(5); // 15
```

Ici, `use ($facteur)` dit explicitement : "cette fonction anonyme doit capturer la valeur de `$facteur` au moment de sa création, et s'en souvenir." C'est ça, une **closure** : une fonction qui "ferme" sur (capture) une partie de son contexte.

### Capture par valeur vs par référence

Par défaut, `use ($variable)` capture une **copie** de la valeur (comme le passage de paramètre classique en PHP) :

```php
$compteur = 0;
$incrementer = function () use ($compteur) {
    $compteur++;
    echo $compteur;
};
$incrementer(); // 1
$incrementer(); // 1 encore ! (la copie capturée ne change jamais)
```

Pour capturer **par référence** (et donc pouvoir modifier la variable d'origine), on ajoute `&` :

```php
$compteur = 0;
$incrementer = function () use (&$compteur) {
    $compteur++;
    echo $compteur;
};
$incrementer(); // 1
$incrementer(); // 2
```

### Fonctions fléchées et closures : capture automatique

Avec `fn`, **pas besoin d'écrire `use`** : toutes les variables externes utilisées dans l'expression sont automatiquement capturées par valeur.

```php
$facteur = 3;
$tripler = fn($n) => $n * $facteur; // $facteur capturée automatiquement
echo $tripler(5); // 15
```

C'est l'un des grands avantages des fonctions fléchées : moins de syntaxe à retenir pour un cas très courant.

---

## 4. Pourquoi c'est pertinent pour ce projet (E-Wallet)

En Partie B, on va remplacer les boucles manuelles par des fonctions natives de tableaux. Ces fonctions natives ont justement besoin de fonctions anonymes ou fléchées comme argument :

```php
// Partie A (procédural, sans fonctions natives) :
function trouverIndexWalletParTelephone(string $telephone, array $tableau): int {
    foreach ($tableau as $index => $wallet) {
        if ($wallet['telephone'] === $telephone) {
            return $index;
        }
    }
    return -1;
}

// Partie B (avec fonction native + fonction fléchée) :
function trouverWalletParTelephone(string $telephone, array $wallets): array|null {
    $resultats = array_filter($wallets, fn($wallet) => $wallet['telephone'] === $telephone);
    return reset($resultats) ?: null;
}
```

La logique est la même, mais on délègue la boucle à PHP lui-même via `array_filter`, et on ne fournit que **le critère** de filtrage sous forme de fonction fléchée.

---

## 5. Résumé

- **Fonction anonyme** : une fonction sans nom, stockée dans une variable ou passée en argument.
- **Fonction fléchée (`fn`)** : version courte d'une fonction anonyme, limitée à une seule expression, avec capture automatique des variables externes.
- **Closure** : le mécanisme sous-jacent qui permet à une fonction de "se souvenir" de variables de son environnement de création, même après l'exécution de cet environnement.
- Indispensables pour utiliser proprement les fonctions natives de tableaux (`array_map`, `array_filter`, `usort`...), qui seront au cœur de la Partie B du projet.
