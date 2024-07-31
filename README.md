# Projet Fil Rouge : Gestion Commande dans un Restaurant

## Description
Ce projet consiste en une application web et mobile pour le Restaurant Brasil Burger, spécialisée dans la gestion des commandes et des livraisons de burgers. L'application permet aux gestionnaires et aux clients de gérer les commandes efficacement.

## Fonctionnalités

### Pour les Gestionnaires
- **Gestion des Produits** : Ajouter, modifier, et archiver des burgers, menus, et compléments (avec nom, prix, et image).
- **Gestion des Commandes** : Suivre, filtrer, et annuler les commandes des clients.
- **Statistiques** : Générer des statistiques journalières sur les commandes et recettes, et imprimer des rapports en PDF.
- **Tickets de Caisse** : Générer des tickets de caisse après paiement.

### Pour les Clients
- **Catalogue** : Consulter le catalogue des burgers, menus, et compléments.
- **Passer Commande** : Commander des burgers et menus, et ajouter des compléments.
- **Suivi des Commandes** : Suivre l'état des commandes en temps réel.
- **Connexion et Gestion des Commandes** : Créer un compte, choisir entre retrait sur place ou livraison.

## Installation

1. **Cloner le dépôt** :
   ```bash
   git clone https://github.com/axeldima10/Brazil_burger.git
2. **Installer les dépendances** :
   ```
   cd yourrepository
   composer install
3. Configurer l'environnement :
   ```
   Renommez le fichier .env.example en .env et configurez les paramètres de votre base de données.
5. Exécuter les migrations
   ```
   php bin/console doctrine:migrations:migrate
6. Lancer le serveur
   ```
   symfony server:start

   
   
