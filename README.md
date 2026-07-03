# FitConnect — Backend PHP / MySQL

Application backend pour le réseau FitConnect (4 salles de sport), permettant
de centraliser la gestion des adhérents, abonnements et séances, avec une
architecture en couches (Entities / Repositories / Services / Controllers).

## Stack technique

- PHP 8.x (POO, sans framework)
- MySQL / MariaDB (PDO, requêtes paramétrées)
- HTML/CSS pour les vues (pas de framework front)

## Arborescence

```
FitConnect/
│
├── app/
│
├── config/
│   └── Database.php
│
├── database/
│   └── fitconnect.sql
│
├── public/
│
├── views/
│   ├── adherents/
│   ├── abonnements/
│   ├── seances/
│   ├── dashboard/
│   └── layouts/
│
├── .gitignore
└── README.md
```
```

## Installation

1. Créer la base de données : importer `database/fitconnect.sql` dans MySQL/MariaDB
   (via phpMyAdmin ou `mysql -u root -p < database/fitconnect.sql`).
2. Adapter les identifiants de connexion dans `config/Database.php`
   (HOST, USER, PASS) si nécessaire.
3. Lancer un serveur PHP local depuis le dossier `public/` :
   ```
   php -S localhost:8000 -t public
   ```
4. Ouvrir `http://localhost:8000/index.php` dans le navigateur.

## Tester les couches indépendamment

```
php public/test.php
```
Ce script vérifie la connexion PDO, le bon fonctionnement des Repositories,
et le respect des règles de gestion par les Services (validité d'abonnement,
blocage de suppression d'un adhérent avec séances/abonnement actif, etc.).

## Règles de gestion implémentées

- Un adhérent est rattaché à une seule salle (parmi les 4 du réseau).
- Un adhérent ne détient qu'un seul abonnement actif à la fois (mensuel,
  trimestriel ou annuel).
- Une séance ne peut être enregistrée que si l'abonnement de l'adhérent est
  valide à la date du jour (vérifié dans `AbonnementService::estAbonnementValide`).
- Un adhérent ne peut pas être supprimé s'il possède des séances enregistrées
  ou un abonnement en cours (vérifié dans `AdherentService::supprimer`).
- Toutes les requêtes SQL sont paramétrées (PDO prepared statements) pour
  prévenir les injections SQL.
- L'intégrité référentielle est garantie par les contraintes FOREIGN KEY
  définies dans `database/fitconnect.sql`.

## Architecture en couches

| Couche       | Rôle                                                            |
|--------------|------------------------------------------------------------------|
| Entities     | Modélisation des données, encapsulation stricte (attributs privés) |
| Repositories | Requêtes SQL via PDO, aucune logique métier                     |
| Services     | Règles de gestion, validations, orchestration métier             |
| Controllers  | Réception des requêtes HTTP, appel des Services, rendu des vues  |

## Auteur

Projet réalisé dans le cadre de la formation DevAcademy — Backend PHP/MySQL.
