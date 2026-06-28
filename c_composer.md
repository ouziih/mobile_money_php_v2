# Le rôle du gestionnaire de dépendances Composer

## 1. Le problème que Composer résout

Quand on développe en PHP, on a presque toujours besoin de **code écrit par d'autres** : une bibliothèque de validation, un client HTTP, un outil de test, un framework. Sans outil dédié, gérer ça manuellement pose plusieurs problèmes :

- Télécharger chaque bibliothèque à la main, et la copier dans son projet.
- Gérer soi-même les **versions** : si la bibliothèque A a besoin de la version 2 de la bibliothèque B, et que la bibliothèque C a besoin de la version 1 de B, comment s'en sortir ?
- Mettre à jour : il faut retélécharger manuellement chaque fois qu'une bibliothèque évolue.
- Inclure tous les fichiers (`require`) dans le bon ordre, en évitant les doublons.

**Composer** est l'outil standard de l'écosystème PHP qui automatise tout ça : il télécharge, installe, met à jour, et organise les bibliothèques dont un projet a besoin — ce qu'on appelle des **dépendances**.

---

## 2. Le fichier `composer.json` : déclarer ses besoins

Tout projet utilisant Composer possède un fichier `composer.json` à sa racine, qui décrit le projet et ses dépendances.

```json
{
    "name": "mon-equipe/e-wallet",
    "description": "Système de gestion de portefeuille électronique",
    "require": {
        "php": ">=8.1"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.0"
    },
    "autoload": {
        "psr-4": {
            "EWallet\\": "src/"
        }
    }
}
```

- **`require`** : les dépendances nécessaires en production (pour que l'application fonctionne).
- **`require-dev`** : les dépendances utiles seulement en développement (tests, analyse de code...), pas nécessaires une fois l'application livrée.
- **`autoload`** : indique à Composer comment charger automatiquement les classes du projet (très lié aux Namespaces, qu'on voit dans la Partie B de ce projet).

---

## 3. Les commandes essentielles

| Commande | Effet |
|---|---|
| `composer init` | Crée un nouveau `composer.json` de façon interactive |
| `composer require nom/paquet` | Ajoute une dépendance et l'installe |
| `composer require --dev nom/paquet` | Ajoute une dépendance de développement uniquement |
| `composer install` | Installe toutes les dépendances listées dans `composer.json` (ex: après avoir cloné un projet Git) |
| `composer update` | Met à jour les dépendances vers leurs dernières versions compatibles |
| `composer dump-autoload` | Régénère le système de chargement automatique des classes |

### Exemple concret

```bash
composer require monolog/monolog
```

Cette seule commande :
1. Cherche le paquet `monolog/monolog` sur Packagist.org.
2. Télécharge la version la plus récente compatible avec le projet.
3. L'installe dans un dossier `vendor/`.
4. Met à jour `composer.json` (ajoute la dépendance) et crée/actualise `composer.lock`.

---

## 4. Le dossier `vendor/` et le fichier `composer.lock`

- **`vendor/`** : dossier où Composer installe physiquement toutes les bibliothèques téléchargées. Il contient aussi `vendor/autoload.php`, le fichier à inclure dans son projet pour avoir accès à toutes les dépendances automatiquement :

```php
require 'vendor/autoload.php';
```

- **`composer.lock`** : fichier généré automatiquement, qui fige les **numéros de version exacts** installés à un instant donné. Il garantit qu'une autre personne (ou un serveur de production) qui installe le projet plus tard obtiendra **exactement les mêmes versions**, évitant les surprises du type "ça marchait sur ma machine".

**Bonne pratique Git** : on versionne `composer.json` et `composer.lock`, mais **jamais** le dossier `vendor/` (il est régénéré via `composer install`). On ajoute donc `vendor/` au fichier `.gitignore`.

---

## 5. Le versionnage sémantique (SemVer) et Composer

Composer s'appuie sur le **versionnage sémantique** (`X.Y.Z`) pour décider quelles mises à jour sont sûres à appliquer automatiquement. Dans `composer.json`, on peut utiliser des contraintes de version :

| Contrainte | Signification |
|---|---|
| `1.2.3` | Exactement cette version |
| `^1.2.3` | Toute version compatible (1.x.x, mais pas 2.0.0) |
| `~1.2.3` | Toute version 1.2.x (patches uniquement) |
| `>=1.0` | Version 1.0 ou supérieure |

C'est directement lié au système SemVer qu'on utilise dans le versionnage Git de ce projet (`v1.0.0`, `v2.0.0`...) : Composer applique exactement la même logique pour décider si une mise à jour de dépendance risque de casser le projet ou non.

---

## 6. Pourquoi c'est pertinent pour la Partie B de ce projet

La Partie B introduit les **Namespaces**. Composer, via la convention **PSR-4**, permet justement d'associer automatiquement un namespace à un dossier de fichiers, sans avoir à écrire des dizaines de `require` manuels :

```json
"autoload": {
    "psr-4": {
        "EWallet\\": "src/"
    }
}
```

Avec cette configuration, une classe (ou un fichier organisé en namespace) dans `src/Services/WalletService.php` sera automatiquement accessible via `EWallet\Services\WalletService`, sans `require` explicite — Composer s'occupe de tout charger via `vendor/autoload.php`.

---

## 7. Résumé

- Composer est le **gestionnaire de dépendances** standard de PHP : il télécharge, installe et met à jour les bibliothèques externes.
- Le fichier `composer.json` déclare ce dont le projet a besoin ; `composer.lock` fige les versions exactes installées.
- Le dossier `vendor/` contient le code téléchargé et ne doit jamais être versionné dans Git.
- Composer utilise le versionnage sémantique (SemVer) pour gérer les compatibilités de version.
- Il simplifie aussi le chargement automatique du code via les namespaces (autoload PSR-4), sujet directement lié à la Partie B de ce projet.
