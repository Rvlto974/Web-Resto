# Vite & Gourmand - Application Web Traiteur

Application web de commande en ligne pour l'entreprise de traiteur **Vite & Gourmand** basée à Bordeaux.

> Projet réalisé dans le cadre du titre professionnel **Développeur Web et Web Mobile**.

---

## Sommaire

- [Présentation](#présentation)
- [Fonctionnalités](#fonctionnalités)
- [Stack Technique](#stack-technique)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Lancement](#lancement)
- [Accès à l'application](#accès-à-lapplication)
- [Comptes de test](#comptes-de-test)
- [Structure du projet](#structure-du-projet)
- [Documentation](#documentation)
- [Auteur](#auteur)

---

## Présentation

**Vite & Gourmand** est une entreprise de traiteur bordelaise dirigée par Julie et José, avec 25 ans d'expérience. Cette application permet aux clients de :

- Consulter les menus proposés
- Commander en ligne
- Suivre leurs commandes
- Laisser des avis

---

## Fonctionnalités

### Visiteur (non authentifié)
- Consulter la liste des menus avec filtres (prix, thème, régime, nb personnes)
- Voir le détail d'un menu (composition, allergènes, prix)
- Utiliser le formulaire de contact
- Créer un compte

### Client (authentifié)
- Commander des menus
- Consulter l'historique de ses commandes
- Annuler/modifier une commande (si non acceptée)
- Suivre l'état de ses commandes
- Laisser des avis après livraison
- Modifier son profil

### Employé
- Gérer les menus et plats (CRUD)
- Gérer les commandes (changement de statut)
- Valider/refuser les avis clients
- Modifier les horaires d'ouverture

### Administrateur
- Créer/désactiver des comptes employés
- Visualiser les statistiques (graphiques)
- Calculer le chiffre d'affaires par menu

---

## Stack Technique

| Composant | Technologie |
|-----------|-------------|
| **Front-end** | HTML5, CSS3 (Bootstrap), JavaScript |
| **Back-end** | PHP 8.2 (Architecture MVC) |
| **BDD Relationnelle** | MySQL 8.0 |
| **BDD NoSQL** | MongoDB 7.0 (statistiques) |
| **Conteneurisation** | Docker & Docker Compose |
| **Serveur Web** | Apache |

---

## Prérequis

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (version 20+)
- [Git](https://git-scm.com/)
- Navigateur web moderne (Chrome, Firefox, Edge)

---

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/Rvlto974/Web-Resto.git
cd Web-Resto
```

### 2. Lancer les conteneurs Docker

```bash
docker-compose up -d
```

Cette commande va :
- Construire l'image PHP/Apache
- Démarrer MySQL et importer automatiquement le schéma + données de test
- Démarrer MongoDB pour les statistiques
- Démarrer phpMyAdmin et Mongo Express

### 3. Vérifier que tout fonctionne

```bash
docker-compose ps
```

Tous les conteneurs doivent être en état `running`.

---

## Lancement

### Démarrer l'application

```bash
docker-compose up -d
```

### Arrêter l'application

```bash
docker-compose down
```

### Voir les logs

```bash
docker-compose logs -f web
```

### Reconstruire après modification

```bash
docker-compose up -d --build
```

---

## Accès à l'application

| Service | URL | Description |
|---------|-----|-------------|
| **Application** | http://localhost:8080 | Site web principal |
| **phpMyAdmin** | http://localhost:8081 | Gestion MySQL |
| **Mongo Express** | http://localhost:8082 | Gestion MongoDB |

### Identifiants phpMyAdmin

- **Serveur** : db
- **Utilisateur** : resto_user
- **Mot de passe** : resto_pass

### Identifiants Mongo Express

Accès direct sans authentification.

---

## Comptes de test

> **Mot de passe pour tous les comptes : `password`**

### Administrateur
- **Email** : admin@viteetgourmand.fr
- **Mot de passe** : password

### Employé
- **Email** : employe@viteetgourmand.fr
- **Mot de passe** : password

### Client
- **Email** : client@test.fr
- **Mot de passe** : password

---

## Structure du projet

```
Web-Resto/
├── config/                 # Configuration (BDD, MongoDB)
│   ├── database.php
│   └── mongodb.php
├── docs/                   # Documentation
│   ├── charte-graphique.html
│   ├── mockups/            # Maquettes haute fidélité (6 fichiers)
│   ├── wireframes/         # Maquettes basse fidélité (6 fichiers)
│   └── uml/                # Diagrammes UML
│       ├── cas-utilisation.md
│       ├── diagramme-classes.md
│       ├── mcd.md
│       └── sequences.md
├── public/                 # Point d'entrée public
│   ├── index.php           # Front controller
│   ├── .htaccess           # Réécriture URL
│   └── assets/             # CSS, JS, images
├── sql/                    # Scripts SQL
│   ├── schema.sql          # Structure de la BDD
│   └── seed.sql            # Données de test
├── src/                    # Code source
│   ├── Controllers/        # Contrôleurs MVC
│   ├── Core/               # Classes utilitaires (Auth, CSRF)
│   ├── Models/             # Modèles (User, Menu, Order...)
│   ├── Services/           # Services (Email...)
│   └── Views/              # Vues PHP
├── docker-compose.yml      # Configuration Docker
├── Dockerfile              # Image PHP/Apache
└── README.md               # Ce fichier
```

---

## Documentation

### Dossier technique (DWWM)
- **Dossier complet** : `docs/DOSSIER-TECHNIQUE.md`

### UML
- **Cas d'utilisation** : `docs/uml/cas-utilisation.md`
- **MCD** : `docs/uml/mcd.md`
- **Diagramme de classes** : `docs/uml/diagramme-classes.md`
- **Diagrammes de séquence** : `docs/uml/sequences.md`

### Maquettes
- **Wireframes** : `docs/wireframes/` (6 fichiers HTML)
- **Mockups** : `docs/mockups/` (6 fichiers HTML)

### Charte graphique
- `docs/charte-graphique.html`

### Manuel utilisateur
- `docs/manuel-utilisateur.html`

---

## Variables d'environnement

| Variable | Description | Valeur par défaut |
|----------|-------------|-------------------|
| `DB_HOST` | Hôte MySQL | db |
| `DB_NAME` | Nom de la BDD | vite_et_gourmand |
| `DB_USER` | Utilisateur MySQL | resto_user |
| `DB_PASSWORD` | Mot de passe MySQL | resto_pass |
| `MONGO_HOST` | Hôte MongoDB | mongodb |
| `MONGO_PORT` | Port MongoDB | 27017 |
| `MONGO_DB` | Base MongoDB | vite_et_gourmand_stats |

---

## Sécurité

- **Mots de passe** : Hashés avec `password_hash()` (bcrypt)
- **Protection CSRF** : Tokens sur tous les formulaires
- **Validation** : Côté serveur sur toutes les entrées
- **Sessions** : Gestion sécurisée avec régénération d'ID
- **SQL** : Requêtes préparées (PDO) contre les injections

---

## Conformité

- **RGPD** : Politique de confidentialité, droit à l'oubli
- **RGAA** : Accessibilité (contrastes, navigation clavier)

---

## Dépannage

### Les conteneurs ne démarrent pas

```bash
# Vérifier les logs
docker-compose logs

# Reconstruire les images
docker-compose build --no-cache
docker-compose up -d
```

### Erreur de connexion à la BDD

```bash
# Vérifier que MySQL est prêt
docker-compose logs db

# Attendre 30 secondes après le premier lancement
```

### Réinitialiser la base de données

```bash
docker-compose down -v
docker-compose up -d
```

---

## Liens

- **Dépôt GitHub** : https://github.com/Rvlto974/Web-Resto
- **Application déployée** : https://vite-gourmand-resto.fly.dev/

---

## Auteur

Projet réalisé par **[Mathieu Jacquet]** dans le cadre du titre professionnel Développeur Web et Web Mobile.

---

## Licence

Ce projet est réalisé à des fins pédagogiques.
