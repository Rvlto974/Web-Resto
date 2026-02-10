# MCD - Modele Conceptuel de Donnees

## Vite & Gourmand - Base de donnees

### Visualisation
Utilise [Mermaid Live Editor](https://mermaid.live) pour visualiser ce diagramme.
Copie le code ci-dessous et colle-le dans l'editeur.

```mermaid
erDiagram
    UTILISATEUR ||--o{ COMMANDE : "passe"
    UTILISATEUR ||--o{ AVIS : "redige"
    MENU ||--o{ COMMANDE : "concerne"
    MENU ||--o{ AVIS : "recoit"
    MENU ||--|{ MENU_PLAT : "contient"
    PLAT ||--|{ MENU_PLAT : "compose"
    PLAT ||--o{ PLAT_ALLERGENE : "possede"
    ALLERGENE ||--o{ PLAT_ALLERGENE : "concerne"
    MENU ||--o{ MENU_IMAGE : "illustre"
    COMMANDE ||--o| AVIS : "genere"

    UTILISATEUR {
        int id PK
        string nom
        string prenom
        string email UK
        string telephone
        string mot_de_passe
        enum role "client|employe|admin"
        string adresse
        string ville
        string code_postal
        boolean actif
        boolean email_verifie
        string token_verification
        string token_reset
        datetime date_creation
        datetime derniere_connexion
    }

    MENU {
        int id PK
        string titre
        text description
        enum theme "noel|paques|classique|evenement|saisonnier"
        enum regime "classique|vegetarien|vegan"
        int min_personnes
        decimal prix_base
        decimal prix_par_personne_sup
        int seuil_reduction
        int stock
        boolean disponible
        int delai_commande_jours
        text instructions_conservation
        string image_principale
        datetime date_creation
        datetime date_modification
    }

    PLAT {
        int id PK
        string nom
        text description
        enum categorie "entree|plat|dessert|boisson|accompagnement"
        decimal prix_unitaire
        string image
        datetime date_creation
    }

    ALLERGENE {
        int id PK
        string nom
        string icone
        datetime date_creation
    }

    COMMANDE {
        int id PK
        string numero UK
        int utilisateur_id FK
        int menu_id FK
        int nb_personnes
        string client_nom
        string client_prenom
        string client_email
        string client_telephone
        string adresse_livraison
        string ville_livraison
        string code_postal_livraison
        date date_livraison
        time heure_livraison
        string lieu_livraison
        decimal prix_base
        decimal reduction
        decimal frais_livraison
        decimal prix_total
        enum statut "nouvelle|acceptee|preparation|livraison|livree|attente_retour|terminee|annulee"
        text notes_client
        text notes_admin
        boolean materiel_rendu
        date date_retour_materiel
        datetime date_creation
        datetime date_modification
    }

    AVIS {
        int id PK
        int utilisateur_id FK
        int commande_id FK UK
        int menu_id FK
        int note "1-5"
        text commentaire
        boolean approuve
        int approuve_par FK
        datetime date_approbation
        datetime date_creation
    }

    MENU_PLAT {
        int menu_id PK,FK
        int plat_id PK,FK
        enum categorie "entree|plat|dessert"
        int ordre_affichage
    }

    PLAT_ALLERGENE {
        int plat_id PK,FK
        int allergene_id PK,FK
    }

    MENU_IMAGE {
        int id PK
        int menu_id FK
        string url_image
        int ordre_affichage
    }

    RESET_MOT_DE_PASSE {
        int id PK
        int utilisateur_id FK
        string token UK
        datetime expiration
        boolean utilise
        datetime date_creation
    }
```

---

## Dictionnaire des donnees

### UTILISATEUR
| Attribut | Type | Description | Contraintes |
|----------|------|-------------|-------------|
| id | INT | Identifiant unique | PK, AUTO_INCREMENT |
| nom | VARCHAR(100) | Nom de famille | NOT NULL |
| prenom | VARCHAR(100) | Prenom | NOT NULL |
| email | VARCHAR(255) | Adresse email | UNIQUE, NOT NULL |
| telephone | VARCHAR(20) | Numero de telephone | NOT NULL |
| mot_de_passe | VARCHAR(255) | Hash du mot de passe | NOT NULL |
| role | ENUM | Role utilisateur | 'client', 'employe', 'admin' |
| adresse | VARCHAR(255) | Adresse postale | NULL |
| ville | VARCHAR(100) | Ville | NULL |
| code_postal | VARCHAR(10) | Code postal | NULL |
| actif | BOOLEAN | Compte actif | DEFAULT TRUE |
| email_verifie | BOOLEAN | Email confirme | DEFAULT FALSE |
| token_verification | VARCHAR(255) | Token de verification email | NULL |
| token_reset | VARCHAR(255) | Token reset mot de passe | NULL |
| date_creation | DATETIME | Date de creation | DEFAULT NOW() |
| derniere_connexion | DATETIME | Derniere connexion | NULL |

### MENU
| Attribut | Type | Description | Contraintes |
|----------|------|-------------|-------------|
| id | INT | Identifiant unique | PK, AUTO_INCREMENT |
| titre | VARCHAR(255) | Nom du menu | NOT NULL |
| description | TEXT | Description complete | NOT NULL |
| theme | ENUM | Theme du menu | 'noel', 'paques', 'classique', 'evenement', 'saisonnier' |
| regime | ENUM | Type de regime | 'classique', 'vegetarien', 'vegan' |
| min_personnes | INT | Minimum de convives | NOT NULL, MIN 1 |
| prix_base | DECIMAL(10,2) | Prix pour min_personnes | NOT NULL |
| prix_par_personne_sup | DECIMAL(10,2) | Prix par personne supplementaire | NOT NULL |
| seuil_reduction | INT | Nb personnes pour -10% | DEFAULT 5 |
| stock | INT | Quantite disponible | NOT NULL |
| disponible | BOOLEAN | Menu visible | DEFAULT TRUE |
| delai_commande_jours | INT | Delai minimum (jours) | DEFAULT 2 |
| instructions_conservation | TEXT | Instructions de stockage | NULL |
| image_principale | VARCHAR(255) | URL image principale | NULL |

### COMMANDE
| Attribut | Type | Description | Contraintes |
|----------|------|-------------|-------------|
| id | INT | Identifiant unique | PK, AUTO_INCREMENT |
| numero | VARCHAR(20) | Numero de commande | UNIQUE, NOT NULL |
| utilisateur_id | INT | Client | FK -> UTILISATEUR |
| menu_id | INT | Menu commande | FK -> MENU |
| nb_personnes | INT | Nombre de convives | NOT NULL |
| statut | ENUM | Etat de la commande | Voir liste des statuts |
| prix_total | DECIMAL(10,2) | Montant final | NOT NULL |

### Statuts de commande
1. `nouvelle` - Commande recue
2. `acceptee` - Validee par l'equipe
3. `preparation` - En cours de preparation
4. `livraison` - En cours de livraison
5. `livree` - Livree au client
6. `attente_retour` - Attente retour materiel
7. `terminee` - Commande finalisee
8. `annulee` - Commande annulee

---

## Export en image

### Option 1 : Mermaid Live Editor
1. Va sur https://mermaid.live
2. Colle le code Mermaid
3. Clique sur "Export" > "PNG" ou "SVG"

### Option 2 : dbdiagram.io
1. Va sur https://dbdiagram.io
2. Convertis le schema en syntaxe DBML
3. Exporte en PNG/PDF

### Option 3 : draw.io
1. Va sur https://draw.io
2. Cree manuellement le schema
3. Exporte en PNG/PDF
