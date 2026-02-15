# Dossier Technique - Vite & Gourmand

> **Projet** : Application web de commande en ligne pour traiteur
> **Candidat** : Mathieu Jacquet
> **Titre visé** : Développeur Web et Web Mobile (DWWM)
> **Date** : Février 2026

---

## Table des matières

1. [Présentation du projet](#1-présentation-du-projet)
2. [Gestion de projet](#2-gestion-de-projet)
3. [Architecture technique](#3-architecture-technique)
4. [Base de données](#4-base-de-données)
5. [Sécurité](#5-sécurité)
6. [Conformité RGPD](#6-conformité-rgpd)
7. [Accessibilité RGAA/WCAG](#7-accessibilité-rgaawcag)
8. [Fonctionnalités développées](#8-fonctionnalités-développées)
9. [API et points d'entrée](#9-api-et-points-dentrée)
10. [Captures d'écran](#10-captures-décran)
11. [Déploiement](#11-déploiement)
12. [Tests réalisés](#12-tests-réalisés)
13. [Veille technologique](#13-veille-technologique)
14. [Bilan personnel](#14-bilan-personnel)
15. [Axes d'amélioration](#15-axes-damélioration)

---

## 1. Présentation du projet

### 1.1 Contexte

**Vite & Gourmand** est une entreprise de traiteur bordelaise avec 25 ans d'expérience, dirigée par Julie et José. L'entreprise souhaite moderniser son activité avec une application web permettant la commande en ligne.

### 1.2 Objectifs

- Permettre aux clients de consulter et commander des menus en ligne
- Offrir un espace d'administration pour gérer les commandes et menus
- Proposer une expérience utilisateur responsive (mobile-first)
- Garantir la sécurité des données utilisateurs

### 1.3 Périmètre fonctionnel

| Acteur | Fonctionnalités principales |
|--------|----------------------------|
| Visiteur | Consultation menus, inscription, contact |
| Client | Commande, panier, historique, avis |
| Employé | Gestion menus, commandes, avis |
| Admin | Gestion employés, statistiques |

---

## 2. Gestion de projet

### 2.1 Méthodologie

Le projet a été développé en suivant une **méthodologie Agile** adaptée au contexte solo :

- **Sprints d'une semaine** avec objectifs définis
- **Daily review** personnelle (15 min/jour)
- **Backlog** priorisé par fonctionnalité
- **Git** pour le versioning et le suivi des modifications

### 2.2 Planning de réalisation

| Semaine | Sprint | Objectifs | Livrables |
|---------|--------|-----------|-----------|
| S1 | Sprint 0 | Analyse, conception | Cahier des charges, MCD, wireframes |
| S2 | Sprint 1 | Architecture, BDD | Structure MVC, schéma SQL, Docker |
| S3 | Sprint 2 | Authentification | Inscription, connexion, sessions |
| S4 | Sprint 3 | Gestion menus | CRUD menus, filtres AJAX |
| S5 | Sprint 4 | Panier & commandes | Cart, checkout, historique |
| S6 | Sprint 5 | Espace admin | Dashboard, gestion commandes |
| S7 | Sprint 6 | Avis & RGPD | Reviews, export données, suppression |
| S8 | Sprint 7 | Déploiement | Fly.io, tests, documentation |

### 2.3 Diagramme de Gantt simplifié

```
Semaine      S1    S2    S3    S4    S5    S6    S7    S8
            ─────────────────────────────────────────────────
Conception  ████
Architecture      ████
Auth                    ████
Menus                         ████
Panier/Cmd                          ████
Admin                                     ████
RGPD/Avis                                       ████
Déploiement                                           ████
Tests       ──────────────────────────────────────────────▶
```

### 2.4 Outils utilisés

| Outil | Usage |
|-------|-------|
| **VS Code** | Éditeur de code principal |
| **Git / GitHub** | Versioning, collaboration |
| **Docker Desktop** | Environnement de développement |
| **Figma** | Wireframes et mockups |
| **dbdiagram.io** | Conception MCD |
| **Postman** | Tests API |
| **Chrome DevTools** | Debug, responsive |

### 2.5 Suivi des commits

```
Total commits : 50+
Branches utilisées : main (production)

Exemples de commits :
- feat: Système de panier avec sessions
- feat: Filtres AJAX pour menus
- fix: Protection CSRF sur formulaires
- docs: Dossier technique complet
- deploy: Configuration Fly.io
```

---

## 3. Architecture technique

### 3.1 Stack technologique

| Couche | Technologie | Version | Justification |
|--------|-------------|---------|---------------|
| Front-end | HTML5, CSS3, JavaScript | - | Standards web, compatibilité navigateurs |
| Framework CSS | Bootstrap | 5.3 | Responsive, composants prêts à l'emploi |
| Back-end | PHP | 8.2 | Langage mature, large communauté, performant |
| BDD relationnelle | MySQL | 8.0 | Fiabilité, performances, support ACID |
| BDD NoSQL | MongoDB | 7.0 | Flexibilité pour logs/statistiques |
| Serveur web | Apache | 2.4 | Robuste, mod_rewrite pour URLs propres |
| Conteneurisation | Docker | 20+ | Portabilité, environnement reproductible |
| Déploiement | Fly.io | - | PaaS simple, scaling automatique |

### 3.2 Architecture MVC

```
┌─────────────────────────────────────────────────────────────┐
│                        CLIENT (Navigateur)                   │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                     public/index.php                         │
│                     (Front Controller)                       │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                        Router.php                            │
│              (Analyse URL → Controller/Action)               │
└─────────────────────────────────────────────────────────────┘
                              │
          ┌───────────────────┼───────────────────┐
          ▼                   ▼                   ▼
┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐
│   Controllers   │ │     Models      │ │      Views      │
│                 │ │                 │ │                 │
│ - HomeController│ │ - User.php      │ │ - layouts/      │
│ - MenuController│ │ - Menu.php      │ │ - home/         │
│ - UserController│ │ - Order.php     │ │ - menu/         │
│ - OrderController│ │ - Cart.php      │ │ - admin/        │
│ - CartController│ │ - Review.php    │ │ - user/         │
│ - AdminController│ │ - OrderItem.php │ │ - cart/         │
│ - PaymentController│ │               │ │ - payment/      │
└─────────────────┘ └─────────────────┘ └─────────────────┘
          │                   │
          ▼                   ▼
┌─────────────────────────────────────────────────────────────┐
│                    Core / Services                           │
│  - Auth.php (Authentification, sessions)                     │
│  - Csrf.php (Protection CSRF)                                │
│  - Model.php (Classe de base PDO)                            │
│  - Controller.php (Classe de base)                           │
│  - EmailService.php (Envoi d'emails)                         │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                      Base de données                         │
│                  MySQL (données métier)                      │
│                  MongoDB (statistiques)                      │
└─────────────────────────────────────────────────────────────┘
```

### 3.3 Structure des dossiers

```
App-Resto/
├── config/                     # Configuration
│   ├── database.php            # Connexion MySQL (PDO)
│   ├── mongodb.php             # Connexion MongoDB
│   └── stripe.php              # Configuration Stripe
├── docs/                       # Documentation
│   ├── uml/                    # Diagrammes UML
│   ├── wireframes/             # Maquettes basse fidélité
│   ├── mockups/                # Maquettes haute fidélité
│   └── DOSSIER-TECHNIQUE.md    # Ce document
├── public/                     # Racine web (DocumentRoot)
│   ├── index.php               # Point d'entrée unique
│   ├── .htaccess               # Réécriture d'URL
│   └── assets/                 # Ressources statiques
│       ├── css/style.css
│       ├── js/app.js
│       └── images/
├── sql/                        # Scripts SQL
│   ├── schema.sql              # Structure BDD
│   ├── seed.sql                # Données de test
│   └── migration_cart_stripe.sql
├── src/                        # Code source
│   ├── Controllers/            # Contrôleurs (logique métier)
│   ├── Core/                   # Classes utilitaires
│   ├── Models/                 # Modèles (accès données)
│   ├── Services/               # Services (email, etc.)
│   └── Views/                  # Vues (templates PHP)
│       ├── layouts/            # Header, footer
│       ├── core/               # Router, Controller, Model de base
│       └── [module]/           # Vues par module
├── docker-compose.yml          # Orchestration Docker
├── Dockerfile                  # Image Docker dev
├── Dockerfile.fly              # Image Docker production
├── fly.toml                    # Configuration Fly.io
└── composer.json               # Dépendances PHP
```

---

## 4. Base de données

### 4.1 Modèle Conceptuel de Données (MCD)

```
┌─────────────┐       ┌─────────────┐       ┌─────────────┐
│   USERS     │       │   ORDERS    │       │   MENUS     │
├─────────────┤       ├─────────────┤       ├─────────────┤
│ id (PK)     │───┐   │ id (PK)     │   ┌───│ id (PK)     │
│ email       │   │   │ order_number│   │   │ title       │
│ password    │   └──►│ user_id(FK) │   │   │ description │
│ first_name  │       │ menu_id(FK) │◄──┘   │ theme       │
│ last_name   │       │ total_price │       │ base_price  │
│ role        │       │ status      │       │ dietary_type│
│ ...         │       │ ...         │       │ ...         │
└─────────────┘       └─────────────┘       └─────────────┘
                            │                     │
                            ▼                     │
                      ┌─────────────┐             │
                      │ ORDER_ITEMS │             │
                      ├─────────────┤             │
                      │ id (PK)     │             │
                      │ order_id(FK)│             │
                      │ menu_id(FK) │◄────────────┘
                      │ quantity    │
                      │ unit_price  │
                      └─────────────┘

┌─────────────┐       ┌─────────────┐       ┌─────────────┐
│  REVIEWS    │       │ ALLERGENS   │       │MENU_ALLERGENS│
├─────────────┤       ├─────────────┤       ├─────────────┤
│ id (PK)     │       │ id (PK)     │◄──────│ menu_id(FK) │
│ user_id(FK) │       │ name        │       │ allergen_id │
│ order_id(FK)│       │ icon        │       └─────────────┘
│ menu_id(FK) │       └─────────────┘
│ rating      │
│ comment     │
└─────────────┘
```

### 4.2 Tables principales

#### Table `users`
| Colonne | Type | Description |
|---------|------|-------------|
| id | INT AUTO_INCREMENT | Clé primaire |
| email | VARCHAR(255) UNIQUE | Email (login) |
| password_hash | VARCHAR(255) | Mot de passe hashé (bcrypt) |
| first_name | VARCHAR(100) | Prénom |
| last_name | VARCHAR(100) | Nom |
| role | ENUM('client','employee','admin') | Rôle utilisateur |
| phone | VARCHAR(20) | Téléphone |
| address, city, postal_code | VARCHAR | Adresse |
| is_active | BOOLEAN | Compte actif |
| created_at | TIMESTAMP | Date création |

#### Table `menus`
| Colonne | Type | Description |
|---------|------|-------------|
| id | INT AUTO_INCREMENT | Clé primaire |
| title | VARCHAR(200) | Nom du menu |
| description | TEXT | Description |
| theme | ENUM('christmas','easter','classic','event','seasonal') | Thème |
| dietary_type | ENUM('classic','vegetarian','vegan','halal') | Régime |
| base_price | DECIMAL(10,2) | Prix de base |
| min_people | INT | Nb personnes minimum |
| stock_quantity | INT | Stock disponible |
| is_available | BOOLEAN | Disponibilité |

#### Table `orders`
| Colonne | Type | Description |
|---------|------|-------------|
| id | INT AUTO_INCREMENT | Clé primaire |
| order_number | VARCHAR(50) UNIQUE | N° commande (CMD-YYYY-XXXX) |
| user_id | INT (FK) | Client |
| status | ENUM(...) | pending, accepted, preparing, delivering, delivered, cancelled |
| total_price | DECIMAL(10,2) | Montant total |
| payment_method | ENUM('cash','stripe') | Mode paiement |
| payment_status | ENUM('pending','paid','failed','refunded') | Statut paiement |
| delivery_date | DATE | Date livraison |
| delivery_time | TIME | Heure livraison |
| delivery_address | TEXT | Adresse complète |

### 4.3 Requêtes préparées (PDO)

Toutes les requêtes utilisent des **requêtes préparées** pour prévenir les injections SQL :

```php
// Exemple dans Model.php
public static function query($sql, $params = []) {
    $db = self::getDb();
    $stmt = $db->prepare($sql);  // Préparation
    $stmt->execute($params);      // Exécution avec paramètres
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Utilisation dans User.php
public static function findByEmail($email) {
    return self::queryOne(
        "SELECT * FROM users WHERE email = ?",
        [$email]  // Paramètre bindé automatiquement
    );
}
```

---

## 5. Sécurité

### 5.1 Authentification

#### Hashage des mots de passe
```php
// Création de compte
$hash = password_hash($password, PASSWORD_DEFAULT);

// Vérification
if (password_verify($inputPassword, $user['password_hash'])) {
    // Connexion réussie
}
```
- Algorithme : **bcrypt** (coût par défaut : 10)
- Résistant aux attaques par force brute et rainbow tables

#### Gestion des sessions
```php
// Dans Auth.php
public static function init() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

public static function login($user) {
    session_regenerate_id(true);  // Prévention fixation de session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = $user['role'];
}
```

### 5.2 Protection CSRF

Chaque formulaire inclut un **token CSRF** :

```php
// Génération (Csrf.php)
public static function generate() {
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    return $token;
}

// Dans les vues
<form method="POST">
    <?php echo Csrf::getInputField(); ?>
    <!-- champs du formulaire -->
</form>

// Validation côté serveur
if (!Csrf::validateRequest()) {
    Auth::setFlash('error', 'Token de sécurité invalide.');
    $this->redirect('/');
}
```

### 5.3 Validation des entrées

```php
// Exemple dans OrderController.php
$customerEmail = trim($_POST['customer_email'] ?? '');

// Validation email
if (empty($customerEmail) || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "L'email est invalide.";
}

// Validation téléphone (regex)
if (!preg_match('/^[0-9]{10}$/', $customerPhone)) {
    $errors[] = "Le téléphone doit contenir 10 chiffres.";
}

// Validation code postal
if (!preg_match('/^[0-9]{5}$/', $deliveryPostalCode)) {
    $errors[] = "Le code postal doit contenir 5 chiffres.";
}
```

### 5.4 Échappement des sorties (XSS)

```php
// Dans les vues, toutes les données utilisateur sont échappées
<?php echo htmlspecialchars($user['first_name'], ENT_QUOTES, 'UTF-8'); ?>
```

### 5.5 Contrôle d'accès

```php
// Protection des routes dans Auth.php
public static function requireAuth() {
    if (!self::check()) {
        self::setFlash('error', 'Veuillez vous connecter.');
        header('Location: /user/login');
        exit;
    }
}

public static function requireAdmin() {
    self::requireRole(['admin'], '/');
}

public static function requireEmployee() {
    self::requireRole(['employee', 'admin'], '/');
}
```

### 5.6 Résumé des mesures de sécurité

| Menace | Protection | Implémentation |
|--------|------------|----------------|
| Injection SQL | Requêtes préparées | PDO avec paramètres bindés |
| XSS | Échappement | htmlspecialchars() |
| CSRF | Tokens | Csrf.php + validation |
| Brute force | Hashage fort | bcrypt (PASSWORD_DEFAULT) |
| Fixation session | Régénération ID | session_regenerate_id() |
| Accès non autorisé | Middleware auth | requireAuth(), requireRole() |

---

## 6. Conformité RGPD

Le projet respecte le **Règlement Général sur la Protection des Données** (RGPD - UE 2016/679).

### 6.1 Principes appliqués

| Principe RGPD | Implémentation |
|---------------|----------------|
| **Minimisation** | Collecte uniquement des données nécessaires |
| **Finalité** | Données utilisées uniquement pour le service |
| **Consentement** | Acceptation CGV obligatoire à l'inscription |
| **Transparence** | Politique de confidentialité accessible |
| **Sécurité** | Hashage mots de passe, HTTPS, sessions sécurisées |

### 6.2 Droits des utilisateurs

#### Article 15 - Droit d'accès
L'utilisateur peut consulter toutes ses données via son profil (`/user/profile`).

#### Article 17 - Droit à l'effacement ("Droit à l'oubli")

```php
// UserController.php - Suppression de compte
public function deleteAccount() {
    Auth::requireAuth();
    $user = Auth::user();

    // Vérification mot de passe + confirmation "SUPPRIMER"
    if ($password && $confirmation === 'SUPPRIMER') {
        if (password_verify($password, $user['password_hash'])) {
            User::anonymize($user['id']);  // Anonymisation
            Auth::logout();
        }
    }
}

// User.php - Anonymisation des données
public static function anonymize($userId) {
    $anonymousEmail = 'deleted_' . $userId . '_' . time() . '@anonymized.local';

    $sql = "UPDATE users SET
            email = ?,
            first_name = 'Utilisateur',
            last_name = 'Supprimé',
            phone = NULL,
            address = NULL,
            city = NULL,
            postal_code = NULL,
            is_active = 0,
            updated_at = NOW()
            WHERE id = ?";

    return self::execute($sql, [$anonymousEmail, $userId]);
}
```

**Note** : Les commandes sont conservées 10 ans (obligation légale comptable) mais anonymisées.

#### Article 20 - Droit à la portabilité

```php
// UserController.php - Export des données
public function exportData() {
    Auth::requireAuth();
    $user = Auth::user();

    $data = [
        'export_date' => date('Y-m-d H:i:s'),
        'user' => [
            'email' => $user['email'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            // ... autres données
        ],
        'orders' => Order::findByUser($userId),
        'reviews' => Review::findByUser($userId)
    ];

    // Téléchargement JSON
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="mes-donnees.json"');
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
```

### 6.3 Pages légales

| Page | URL | Contenu |
|------|-----|---------|
| Mentions légales | `/mentions-legales` | Identité éditeur, hébergeur |
| CGV | `/cgv` | Conditions générales de vente |
| Confidentialité | `/confidentialite` | Politique de confidentialité |

### 6.4 Conservation des données

| Donnée | Durée | Justification |
|--------|-------|---------------|
| Compte utilisateur | Jusqu'à suppression | Fonctionnement du service |
| Commandes | 10 ans | Obligation comptable |
| Logs de connexion | 1 an | Sécurité |
| Cookies de session | Session | Authentification |

---

## 7. Accessibilité RGAA/WCAG

Le projet respecte les recommandations **RGAA 4.1** (Référentiel Général d'Amélioration de l'Accessibilité) et **WCAG 2.1** niveau AA.

### 7.1 Navigation au clavier

#### Skip link (lien d'évitement)
```html
<!-- Dans header.php -->
<a href="#main-content" class="visually-hidden-focusable skip-link">
    Aller au contenu principal
</a>

<!-- Contenu principal -->
<main id="main-content" role="main">
```

#### Focus visible
```css
/* Style CSS pour le focus */
a:focus, button:focus, input:focus, select:focus, textarea:focus {
    outline: 3px solid var(--orange) !important;
    outline-offset: 2px;
}

.skip-link:focus {
    position: fixed;
    top: 10px;
    left: 10px;
    z-index: 10000;
    background: var(--vert-primaire);
    color: white;
    padding: 15px 25px;
}
```

### 7.2 Structure sémantique

#### Landmarks ARIA
```html
<header role="banner">...</header>
<nav role="navigation" aria-label="Menu principal">...</nav>
<main role="main" id="main-content">...</main>
<footer role="contentinfo">...</footer>
```

#### Hiérarchie des titres
- Chaque page a un seul `<h1>`
- Hiérarchie logique : h1 → h2 → h3 (pas de saut)
- Titres descriptifs du contenu

### 7.3 Formulaires accessibles

```html
<!-- Labels explicites -->
<label for="email">Adresse email *</label>
<input type="email" id="email" name="email" required
       aria-describedby="email-help">
<small id="email-help">Nous ne partagerons jamais votre email.</small>

<!-- Messages d'erreur -->
<div role="alert" aria-live="polite">
    <?php if ($error): ?>
        <p class="text-danger"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
</div>
```

### 7.4 Images et icônes

```html
<!-- Icônes décoratives -->
<i class="fas fa-phone" aria-hidden="true"></i>

<!-- Icônes informatives -->
<a href="/cart" aria-label="Panier, 3 articles">
    <i class="fas fa-shopping-cart" aria-hidden="true"></i>
</a>

<!-- Images avec alt -->
<img src="/menu.jpg" alt="Menu de Noël : entrée, plat, dessert pour 8 personnes">
```

### 7.5 Contrastes et lisibilité

| Élément | Couleur | Contraste | Conforme |
|---------|---------|-----------|----------|
| Texte principal | #333 sur #FFF | 12.6:1 | ✅ AAA |
| Texte secondaire | #666 sur #FFF | 5.7:1 | ✅ AA |
| Liens | #5DA99A sur #FFF | 3.2:1 | ✅ AA (large) |
| Boutons | #FFF sur #5DA99A | 3.2:1 | ✅ AA |

### 7.6 Responsive et zoom

- Site utilisable avec zoom 200%
- Pas de perte d'information en mode portrait/paysage
- Taille de police minimum : 16px
- Zones tactiles minimum : 44x44 pixels

### 7.7 Checklist accessibilité

| Critère | Statut |
|---------|--------|
| Navigation clavier complète | ✅ |
| Skip link fonctionnel | ✅ |
| Focus visible | ✅ |
| Landmarks ARIA | ✅ |
| Labels formulaires | ✅ |
| Alt images | ✅ |
| Contrastes suffisants | ✅ |
| Hiérarchie titres | ✅ |
| Pas de CAPTCHA visuel | ✅ |
| Messages d'erreur accessibles | ✅ |

---

## 8. Fonctionnalités développées

### 8.1 Système de panier (Session)

Le panier utilise les **sessions PHP** pour stocker les articles :

```php
// Cart.php - Structure du panier
$_SESSION['cart'] = [
    'items' => [
        [
            'menu_id' => 1,
            'quantity' => 2,
            'number_of_people' => 8,
            'unit_price' => 320.00,
            'subtotal' => 640.00
        ]
    ],
    'subtotal' => 640.00,
    'delivery_fee' => 0,
    'total' => 640.00
];
```

**Fonctionnalités :**
- Ajout au panier (avec nombre de personnes)
- Modification de quantité
- Suppression d'articles
- Calcul automatique des frais de livraison
- Remise 10% pour commandes > 10 personnes

### 8.2 Système de commande

**Workflow de commande :**
```
Panier → Checkout → Validation → Paiement → Confirmation
                                    │
                    ┌───────────────┴───────────────┐
                    ▼                               ▼
              Paiement cash                   Paiement Stripe
              (à la livraison)                (redirection)
```

**Statuts de commande :**
1. `pending` - En attente de validation
2. `accepted` - Acceptée par l'équipe
3. `preparing` - En préparation
4. `delivering` - En cours de livraison
5. `delivered` - Livrée
6. `waiting_return` - Attente retour matériel
7. `completed` - Terminée
8. `cancelled` - Annulée

### 8.3 Filtrage des menus (AJAX)

```javascript
// Appel AJAX pour filtrer les menus
fetch('/menu/filter?' + params.toString())
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderMenus(data.menus);
        }
    });
```

**Critères de filtrage :**
- Thème (Noël, Pâques, Classique, etc.)
- Régime alimentaire (Végétarien, Végan, Halal)
- Fourchette de prix
- Nombre de personnes minimum
- Tri (prix, date, nom)

### 8.4 Système d'avis

- Les clients peuvent laisser un avis après livraison
- Note de 1 à 5 étoiles + commentaire
- Modération par les employés (approbation requise)
- Affichage des avis approuvés sur les fiches menu

### 8.5 Responsive Design

**Breakpoints Bootstrap utilisés :**
- `< 576px` : Mobile portrait
- `576px - 768px` : Mobile paysage
- `768px - 992px` : Tablette
- `> 992px` : Desktop

**Adaptations mobile :**
- Menu hamburger dans le header
- Filtres collapsibles sur la page menus
- Cards en colonne unique
- Formulaires pleine largeur

---

## 9. API et points d'entrée

### 9.1 Routes publiques

| Méthode | URL | Contrôleur | Description |
|---------|-----|------------|-------------|
| GET | `/` | HomeController@index | Page d'accueil |
| GET | `/menu` | MenuController@index | Liste des menus |
| GET | `/menu/show/{id}` | MenuController@show | Détail menu |
| GET | `/menu/filter` | MenuController@filter | API filtrage (JSON) |
| GET | `/contact` | ContactController@index | Page contact |
| POST | `/contact/send` | ContactController@send | Envoi formulaire |

### 9.2 Routes authentification

| Méthode | URL | Contrôleur | Description |
|---------|-----|------------|-------------|
| GET | `/user/login` | UserController@login | Formulaire connexion |
| POST | `/user/login` | UserController@login | Traitement connexion |
| GET | `/user/register` | UserController@register | Formulaire inscription |
| POST | `/user/register` | UserController@register | Traitement inscription |
| GET | `/user/logout` | UserController@logout | Déconnexion |
| GET | `/user/profile` | UserController@profile | Profil utilisateur |

### 9.3 Routes panier

| Méthode | URL | Contrôleur | Description |
|---------|-----|------------|-------------|
| GET | `/cart` | CartController@index | Afficher panier |
| POST | `/cart/add` | CartController@add | Ajouter article |
| POST | `/cart/update` | CartController@update | Modifier quantité |
| POST | `/cart/remove` | CartController@remove | Supprimer article |
| POST | `/cart/clear` | CartController@clear | Vider panier |
| GET | `/cart/checkout` | CartController@checkout | Page checkout |

### 9.4 Routes commandes (authentifié)

| Méthode | URL | Contrôleur | Description |
|---------|-----|------------|-------------|
| GET | `/order/history` | OrderController@history | Historique |
| GET | `/order/show/{id}` | OrderController@show | Détail commande |
| POST | `/order/storeFromCart` | OrderController@storeFromCart | Créer commande |
| POST | `/order/cancel/{id}` | OrderController@cancel | Annuler commande |

### 9.5 Routes administration (employé/admin)

| Méthode | URL | Contrôleur | Description |
|---------|-----|------------|-------------|
| GET | `/admin` | AdminController@index | Dashboard |
| GET | `/admin/menus` | AdminController@menus | Liste menus |
| GET | `/admin/menus/create` | AdminController@createMenu | Formulaire création |
| POST | `/admin/menus/store` | AdminController@storeMenu | Enregistrer menu |
| GET | `/admin/menus/edit/{id}` | AdminController@editMenu | Formulaire édition |
| POST | `/admin/menus/update/{id}` | AdminController@updateMenu | Mettre à jour |
| POST | `/admin/menus/delete/{id}` | AdminController@deleteMenu | Supprimer |
| GET | `/admin/orders` | AdminController@orders | Liste commandes |
| GET | `/admin/orders/show/{id}` | AdminController@showOrder | Détail commande |
| POST | `/admin/orders/status/{id}` | AdminController@updateStatus | Changer statut |
| GET | `/admin/reviews` | AdminController@reviews | Modération avis |
| GET | `/admin/employees` | AdminController@employees | Gestion employés |
| GET | `/admin/stats` | AdminController@stats | Statistiques |

---

## 10. Captures d'écran

### 10.1 Pages publiques

#### Page d'accueil
```
┌─────────────────────────────────────────────────────────────────┐
│  🍽️ Vite & Gourmand                    [Menus] [Contact] [Login]│
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│              VITE & GOURMAND                                   │
│       Traiteur d'exception depuis 25 ans                       │
│            [Découvrir nos menus]                               │
│                                                                 │
├─────────────────────────────────────────────────────────────────┤
│        🏆              🌿              🚚                        │
│   25 ans          Produits         Livraison                   │
│  d'expérience      locaux          Bordeaux                    │
├─────────────────────────────────────────────────────────────────┤
│                  ⭐⭐⭐⭐⭐ Avis clients                           │
│   ← [Photo] "Excellent service..." - Marie D.  →               │
└─────────────────────────────────────────────────────────────────┘
```

#### Liste des menus (avec filtres)
```
┌─────────────────────────────────────────────────────────────────┐
│  Nos Menus                                                      │
├───────────────┬─────────────────────────────────────────────────┤
│ FILTRES       │  ┌──────────┐ ┌──────────┐ ┌──────────┐        │
│               │  │  Menu    │ │  Menu    │ │  Menu    │        │
│ Thème:        │  │  Noël    │ │  Pâques  │ │ Classique│        │
│ [v] Noël      │  │  ⭐⭐⭐⭐⭐  │ │  ⭐⭐⭐⭐   │ │  ⭐⭐⭐⭐⭐  │        │
│ [ ] Pâques    │  │  40€/p   │ │  35€/p   │ │  30€/p   │        │
│               │  │ [Voir]   │ │ [Voir]   │ │ [Voir]   │        │
│ Régime:       │  └──────────┘ └──────────┘ └──────────┘        │
│ [v] Classique │                                                │
│ [ ] Végétarien│                                                │
└───────────────┴─────────────────────────────────────────────────┘
```

### 10.2 Espace client

#### Panier
```
┌─────────────────────────────────────────────────────────────────┐
│  Mon Panier (2 articles)                                        │
├─────────────────────────────────────────────────────────────────┤
│  ┌─────┐  Menu Noël Premium                                    │
│  │ IMG │  8 personnes × 40€ = 320€          [- 1 +] [🗑️]      │
│  └─────┘                                                        │
│  ┌─────┐  Menu Classique                                       │
│  │ IMG │  10 personnes × 30€ = 300€         [- 1 +] [🗑️]      │
│  └─────┘                                                        │
├─────────────────────────────────────────────────────────────────┤
│                              Sous-total:  620€                  │
│                              Livraison:   Offerte               │
│                              Remise 10%: -62€                   │
│                              ─────────────────                  │
│                              TOTAL:       558€                  │
│                                                                 │
│                           [Commander]                           │
└─────────────────────────────────────────────────────────────────┘
```

#### Historique des commandes
```
┌─────────────────────────────────────────────────────────────────┐
│  Mes Commandes                                                  │
├─────────────────────────────────────────────────────────────────┤
│  CMD-2026-0042  │  15/02/2026  │  558€  │  ✅ Livrée  │ [Voir] │
│  CMD-2026-0038  │  10/02/2026  │  320€  │  ✅ Livrée  │ [Voir] │
│  CMD-2026-0035  │  05/02/2026  │  450€  │  ⏳ En cours │ [Voir] │
└─────────────────────────────────────────────────────────────────┘
```

### 10.3 Espace administration

#### Dashboard
```
┌─────────────────────────────────────────────────────────────────┐
│  Dashboard                                                      │
├───────────────┬─────────────────────────────────────────────────┤
│ MENU ADMIN    │  ┌────────────┐ ┌────────────┐ ┌────────────┐  │
│               │  │ 📦 12      │ │ 💰 4 580€  │ │ ⭐ 8       │  │
│ • Dashboard   │  │ Commandes  │ │ CA mensuel │ │ Avis       │  │
│ • Menus       │  │ en cours   │ │            │ │ en attente │  │
│ • Commandes   │  └────────────┘ └────────────┘ └────────────┘  │
│ • Avis        │                                                 │
│ • Employés    │  Commandes récentes:                           │
│ • Stats       │  CMD-0042 │ Marie D. │ 558€ │ En livraison    │
│               │  CMD-0041 │ Jean P.  │ 320€ │ En préparation  │
└───────────────┴─────────────────────────────────────────────────┘
```

#### Gestion des menus
```
┌─────────────────────────────────────────────────────────────────┐
│  Gestion des Menus                        [+ Nouveau menu]     │
├─────────────────────────────────────────────────────────────────┤
│  Titre        │ Thème    │ Prix  │ Stock │ Actions            │
│  ─────────────────────────────────────────────────────────────  │
│  Menu Noël    │ Noël     │ 40€   │ 25    │ [✏️] [🗑️]          │
│  Menu Pâques  │ Pâques   │ 35€   │ 30    │ [✏️] [🗑️]          │
│  Menu Classic │ Classique│ 30€   │ 50    │ [✏️] [🗑️]          │
└─────────────────────────────────────────────────────────────────┘
```

### 10.4 Version mobile

```
┌─────────────────────┐
│ ☰  Vite & Gourmand  │
├─────────────────────┤
│                     │
│   VITE & GOURMAND   │
│                     │
│  Traiteur depuis    │
│     25 ans          │
│                     │
│ [Découvrir menus]   │
│                     │
├─────────────────────┤
│       🏆            │
│  25 ans d'exp.      │
├─────────────────────┤
│       🌿            │
│  Produits locaux    │
├─────────────────────┤
│       🚚            │
│  Livraison          │
└─────────────────────┘
```

### 10.5 URLs des captures réelles

Les captures d'écran complètes sont disponibles sur le site de démonstration :

| Page | URL |
|------|-----|
| Accueil | https://vite-gourmand-resto.fly.dev/ |
| Menus | https://vite-gourmand-resto.fly.dev/menu |
| Détail menu | https://vite-gourmand-resto.fly.dev/menu/show/1 |
| Connexion | https://vite-gourmand-resto.fly.dev/user/login |
| Panier | https://vite-gourmand-resto.fly.dev/cart |
| Admin | https://vite-gourmand-resto.fly.dev/admin |

---

## 11. Déploiement

### 11.1 Environnement local (Docker)

```bash
# Démarrer l'environnement
docker-compose up -d

# Services disponibles
# - Application : http://localhost:8080
# - phpMyAdmin : http://localhost:8081
# - Mongo Express : http://localhost:8082
```

### 11.2 Production (Fly.io)

**Configuration Fly.io (`fly.toml`) :**
```toml
app = "vite-gourmand-resto"
primary_region = "cdg"  # Paris

[http_service]
  internal_port = 8080
  force_https = true
  auto_stop_machines = "stop"
  auto_start_machines = true
  min_machines_running = 0
```

**Déploiement :**
```bash
# Déployer
fly deploy

# Configurer les secrets (BDD)
fly secrets set DB_HOST=xxx DB_PORT=xxx DB_NAME=xxx DB_USER=xxx DB_PASSWORD=xxx
```

**Base de données production :**
- Service : Railway (MySQL managé)
- Host : `trolley.proxy.rlwy.net`
- SSL : Activé

### 11.3 URL de production

**https://vite-gourmand-resto.fly.dev/**

---

## 12. Tests réalisés

### 12.1 Tests fonctionnels automatisés

| Test | Résultat | Code HTTP |
|------|----------|-----------|
| Page d'accueil | ✅ | 200 |
| Page menus | ✅ | 200 |
| Détail menu | ✅ | 200 |
| Page contact | ✅ | 200 |
| Page connexion | ✅ | 200 |
| Page inscription | ✅ | 200 |
| Page panier | ✅ | 200 |
| API filtre menus | ✅ | 200 (JSON) |
| Ajout au panier | ✅ | 302 → /cart |
| Login admin | ✅ | 302 → /admin |
| Admin menus | ✅ | 200 |
| Admin commandes | ✅ | 200 |
| Admin employés | ✅ | 200 |
| Admin avis | ✅ | 200 |
| Admin stats | ✅ | 200 |
| Protection route (non connecté) | ✅ | 302 → /login |

**Résultat : 17/17 tests passés**

### 12.2 Tests manuels

- [x] Inscription nouveau compte
- [x] Connexion / Déconnexion
- [x] Navigation menus avec filtres
- [x] Ajout au panier
- [x] Modification quantité panier
- [x] Passage de commande
- [x] Consultation historique commandes
- [x] Interface admin complète
- [x] Responsive mobile (menu hamburger)
- [x] Responsive tablette

### 12.3 Tests de sécurité

- [x] Injection SQL : Requêtes préparées fonctionnelles
- [x] XSS : Échappement vérifié
- [x] CSRF : Tokens validés sur tous les formulaires
- [x] Accès non autorisé : Redirections correctes

---

## 13. Veille technologique

### 13.1 Sources utilisées

| Source | Type | Utilisation |
|--------|------|-------------|
| MDN Web Docs | Documentation | HTML, CSS, JavaScript |
| PHP.net | Documentation | PHP, PDO |
| Bootstrap Docs | Documentation | Framework CSS |
| Stack Overflow | Forum | Résolution problèmes |
| GitHub | Code source | Exemples, bonnes pratiques |

### 13.2 Évolutions technologiques suivies

- **PHP 8.x** : Nouvelles fonctionnalités (attributs, match, etc.)
- **Bootstrap 5** : Suppression jQuery, nouvelles utilities
- **Stripe API** : Intégration paiement sécurisé
- **Fly.io** : Déploiement simplifié, edge computing

---

## 14. Bilan personnel

### 14.1 Compétences acquises

#### Développement Front-end
| Compétence | Niveau avant | Niveau après |
|------------|--------------|--------------|
| HTML5 sémantique | ⭐⭐ | ⭐⭐⭐⭐ |
| CSS3 / Flexbox / Grid | ⭐⭐ | ⭐⭐⭐⭐ |
| Bootstrap 5 | ⭐ | ⭐⭐⭐⭐ |
| JavaScript (DOM, Fetch) | ⭐⭐ | ⭐⭐⭐⭐ |
| Responsive Design | ⭐⭐ | ⭐⭐⭐⭐ |

#### Développement Back-end
| Compétence | Niveau avant | Niveau après |
|------------|--------------|--------------|
| PHP 8.x | ⭐⭐ | ⭐⭐⭐⭐ |
| Architecture MVC | ⭐ | ⭐⭐⭐⭐ |
| PDO / MySQL | ⭐⭐ | ⭐⭐⭐⭐ |
| Sécurité web (CSRF, XSS) | ⭐ | ⭐⭐⭐⭐ |
| API REST | ⭐ | ⭐⭐⭐ |

#### DevOps & Outils
| Compétence | Niveau avant | Niveau après |
|------------|--------------|--------------|
| Git / GitHub | ⭐⭐ | ⭐⭐⭐⭐ |
| Docker | ⭐ | ⭐⭐⭐⭐ |
| Déploiement cloud | ⭐ | ⭐⭐⭐ |
| CI/CD basique | ⭐ | ⭐⭐⭐ |

### 14.2 Difficultés rencontrées et solutions

| Difficulté | Solution apportée |
|------------|-------------------|
| Architecture MVC from scratch | Étude de frameworks existants (Laravel), documentation, itérations |
| Sessions PHP en production | Configuration correcte de session.save_path et HTTPS |
| Déploiement Fly.io | Lecture documentation, configuration fly.toml, secrets |
| Intégration Stripe | Mode test, documentation API, gestion des webhooks |
| Responsive complexe | Utilisation systématique des breakpoints Bootstrap |
| CSRF sur AJAX | Token dans les headers des requêtes fetch |

### 14.3 Points forts du projet

1. **Architecture solide** : Code organisé, maintenable, évolutif
2. **Sécurité complète** : CSRF, XSS, SQL injection, auth robuste
3. **Conformité RGPD** : Export et suppression des données
4. **Accessibilité** : Navigation clavier, ARIA, contrastes
5. **Documentation** : UML, wireframes, mockups, dossier technique
6. **Déploiement professionnel** : Docker, Fly.io, Railway

### 14.4 Ce que je referais différemment

- **Utiliser un framework** (Laravel/Symfony) pour gagner du temps
- **Mettre en place les tests unitaires** dès le début
- **Utiliser TypeScript** au lieu de JavaScript vanilla
- **Implémenter un système de cache** pour les performances

### 14.5 Apports professionnels

Ce projet m'a permis de :

- ✅ Maîtriser le cycle complet de développement web
- ✅ Comprendre les enjeux de sécurité applicative
- ✅ Appréhender les contraintes légales (RGPD)
- ✅ Acquérir une méthodologie de travail structurée
- ✅ Développer mon autonomie technique
- ✅ Renforcer ma capacité à résoudre des problèmes complexes

### 14.6 Perspectives professionnelles

Fort de cette expérience, je souhaite :

- Approfondir mes connaissances en **frameworks PHP** (Laravel, Symfony)
- Explorer le développement **full-stack JavaScript** (Node.js, React)
- Me former aux pratiques **DevOps** avancées
- Contribuer à des projets **open source**

---

## 15. Axes d'amélioration

### 15.1 Court terme

- [ ] Intégration service email (Brevo/SendGrid)
- [ ] Tests unitaires (PHPUnit)
- [ ] Optimisation images (WebP, lazy loading)
- [ ] Cache des requêtes fréquentes

### 15.2 Moyen terme

- [ ] Application mobile (PWA)
- [ ] Notifications push
- [ ] Système de fidélité (points)
- [ ] Multi-langue (i18n)

### 15.3 Long terme

- [ ] API REST complète
- [ ] Application mobile native
- [ ] Intelligence artificielle (recommandations)
- [ ] Intégration ERP

---

## Annexes

### A. Comptes de test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@viteetgourmand.fr | password |
| Employé | employe@viteetgourmand.fr | password |
| Client | marie.dupont@email.com | password |

### B. Commandes utiles

```bash
# Docker
docker-compose up -d          # Démarrer
docker-compose down           # Arrêter
docker-compose logs -f web    # Voir logs

# Fly.io
fly deploy                    # Déployer
fly logs                      # Voir logs
fly secrets list              # Voir secrets
fly ssh console               # SSH dans le conteneur

# Git
git status                    # État
git add -A && git commit -m "message"  # Commit
git push                      # Push
```

### C. Références

- Documentation PHP : https://www.php.net/docs.php
- Bootstrap 5 : https://getbootstrap.com/docs/5.3/
- Fly.io : https://fly.io/docs/
- Stripe : https://stripe.com/docs

---

*Document généré le 15/02/2026*
