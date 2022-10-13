Projet réalisé dans le cadre de l'ECF Décembre 2022.

## Tech Stack

- **Frontend:** HTML / CSS / JavaScript / Bootstrap 5 / WebPack Encore
- **Moteur de Template:** Twig
- **Backend:** PHP 8.1 / Symfony 6.1 / Composer / ORM Doctrine
- **Base de données :** MySQL

## Authors

Ludovic DAUB

## Pré-Requis

- PHP >= 8.0
- Composer >= 2
- Npm
- MySQL
- WebPack Encore (JS / CSS)

<hr>

## Installation

Suivez les étapes ci-dessous pour installer localement mon projet et le tester.

### Cloner le projet

```bash
  git clone https://github.com/LudovicDaub/studisport.git
```

## Installation des dépendances

### 1. Symfony

```bash
  composer install
```

### 2. Javascript

```bash
  # Avec npm
  npm install
```

### 3. Compiler les Assets

```bash
  # Avec npm
  npm run build
```

## Création de la base de données

Pour créer la base de données, il faut au préalable démarrer le serveur MySQL s'il ne l'est pas.

```bash
php bin/console console doctrine:database:create
php bin/console console doctrine:migrations:migrate
```

### Charger des datas en base de données

```bash
php bin/console console doctrine:fixtures:load -n
```

## Lancer l'application

Pour démarrer l'application

```bash
  symfony serve -d
```

## Explications du sujet

> Dans le cadre de ma formation Développeur Web – Web Mobile, j’ai réalisé une Evaluation en Cours de Formation que j’ai débuté en août 2022 jusqu’en octobre 2022, « StudiSport ». Il m’a été confié par mon école, Studi, via un cahier des charges pour le rendre le plus proche du réel possible.

> Le projet « StudiSport » est une application web de gestion de permissions pour les différentes salles de sport d’un même groupe. L’administrateur y gère les permissions accordées aux franchises et / ou structures selon les contrats. Les franchises s’y connectent pour visualiser les permissions globales et celles de l’ensemble de leurs structures. Les structures quant à elles peuvent voir leurs permissions.

> Le projet a été réalisé avec le framework SYMFONY.
> La partie FRONT-END a été réalisée en HTML / CSS / JavaScript / Bootstrap / Twig / Encore.
> La partie BACK-END a été réalisée en MySQL / Symfony / Composer, ainsi que l’ORM Doctrine pour la création et gestion de la BDD (fichiers de migration).

## Cahier des charges

> Je devais réaliser une application web qui permet de gérer les permissions de franchises et de structures et la gestion des utilisateurs (ajout, mise à jour, suppression) par un administrateur, la lecture seule des informations par les franchises et les structures, le tout géré par une authentification. Chaque ajout de franchise, structure ou modification de permission entraine automatique un mailing à l’utilisateur concerné. Il fallait aussi intégrer une fonction de recherche / tri dynamique des franchises et structures.
