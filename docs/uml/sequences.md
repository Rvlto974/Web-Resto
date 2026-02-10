# Diagrammes de Sequence

## Vite & Gourmand - Flux principaux

### Visualisation
Utilise [Mermaid Live Editor](https://mermaid.live) pour visualiser ces diagrammes.

---

## 1. Sequence : Inscription Utilisateur

```mermaid
sequenceDiagram
    autonumber
    actor V as Visiteur
    participant F as Formulaire<br/>Inscription
    participant UC as UserController
    participant VS as ValidationService
    participant UM as User Model
    participant DB as Base de donnees
    participant ES as EmailService

    V->>F: Accede a /register
    F->>V: Affiche formulaire

    V->>F: Remplit formulaire<br/>(nom, email, mdp, tel)
    F->>UC: POST /register

    UC->>VS: validateRequired(data)
    VS-->>UC: OK

    UC->>VS: validateEmail(email)
    VS-->>UC: OK

    UC->>VS: validatePassword(password)
    alt Mot de passe invalide
        VS-->>UC: Erreurs (min 10 car, maj, min, chiffre, special)
        UC-->>F: Affiche erreurs
        F-->>V: Formulaire avec erreurs
    else Mot de passe valide
        VS-->>UC: OK
    end

    UC->>UM: findByEmail(email)
    UM->>DB: SELECT * FROM users WHERE email = ?
    DB-->>UM: Resultat

    alt Email deja utilise
        UM-->>UC: User existe
        UC-->>F: Erreur "Email deja utilise"
        F-->>V: Formulaire avec erreur
    else Email disponible
        UM-->>UC: null
    end

    UC->>UM: hashPassword(password)
    UM-->>UC: Hash bcrypt

    UC->>UM: generateVerificationToken()
    UM-->>UC: Token unique

    UC->>UM: create(userData)
    UM->>DB: INSERT INTO users (...)
    DB-->>UM: ID utilisateur
    UM-->>UC: User cree

    UC->>ES: sendWelcome(user)
    ES-->>UC: Email envoye

    UC-->>F: Redirect /login
    F-->>V: "Compte cree ! Verifiez votre email"
```

### Description du flux
1. Le visiteur accede au formulaire d'inscription
2. Il remplit ses informations personnelles
3. Le systeme valide :
   - Champs obligatoires
   - Format email
   - Complexite mot de passe (10 car, maj, min, chiffre, special)
4. Verification que l'email n'est pas deja utilise
5. Hashage du mot de passe avec bcrypt
6. Creation du compte en base
7. Envoi de l'email de bienvenue
8. Redirection vers la page de connexion

---

## 2. Sequence : Connexion Utilisateur

```mermaid
sequenceDiagram
    autonumber
    actor V as Visiteur
    participant F as Formulaire<br/>Connexion
    participant UC as UserController
    participant UM as User Model
    participant DB as Base de donnees
    participant Auth as Auth
    participant S as Session

    V->>F: Accede a /login
    F->>V: Affiche formulaire

    V->>F: Entre email + mot de passe
    F->>UC: POST /login

    UC->>UM: findByEmail(email)
    UM->>DB: SELECT * FROM users WHERE email = ?
    DB-->>UM: User ou null

    alt Utilisateur non trouve
        UM-->>UC: null
        UC-->>F: Erreur "Identifiants incorrects"
        F-->>V: Formulaire avec erreur
    else Utilisateur trouve
        UM-->>UC: User
    end

    UC->>UM: verifyPassword(password, hash)

    alt Mot de passe incorrect
        UM-->>UC: false
        UC-->>F: Erreur "Identifiants incorrects"
        F-->>V: Formulaire avec erreur
    else Mot de passe correct
        UM-->>UC: true
    end

    alt Compte desactive
        UC-->>F: Erreur "Compte desactive"
        F-->>V: Formulaire avec erreur
    end

    UC->>Auth: login(user)
    Auth->>S: Stocke user_id en session
    S-->>Auth: OK

    UC->>UM: updateLastLogin(userId)
    UM->>DB: UPDATE users SET last_login = NOW()
    DB-->>UM: OK

    UC-->>F: Redirect /
    F-->>V: Page d'accueil (connecte)
```

---

## 3. Sequence : Passage de Commande

```mermaid
sequenceDiagram
    autonumber
    actor C as Client
    participant P as Page Menu
    participant F as Formulaire<br/>Commande
    participant OC as OrderController
    participant MM as Menu Model
    participant OM as Order Model
    participant DB as Base de donnees
    participant ES as EmailService
    participant Stats as Statistics<br/>(MongoDB)

    C->>P: Clique "Commander" sur menu

    alt Non connecte
        P-->>C: Redirect /login
    end

    P->>OC: GET /order/create/{menuId}
    OC->>MM: findById(menuId)
    MM->>DB: SELECT * FROM menus
    DB-->>MM: Menu
    MM-->>OC: Menu

    OC-->>F: Affiche formulaire pre-rempli
    F-->>C: Formulaire (infos client pre-remplies)

    C->>F: Complete formulaire<br/>(nb personnes, adresse, date)
    F->>OC: POST /order/store

    OC->>OC: Valide donnees

    OC->>OM: calculatePrice(menuId, nbPersonnes, distance)
    Note over OM: Prix base<br/>+ extras<br/>- reduction 10% si +5 pers<br/>+ livraison si hors Bordeaux
    OM-->>OC: {base, reduction, livraison, total}

    OC->>MM: checkStock(menuId)
    MM->>DB: SELECT stock FROM menus
    DB-->>MM: Stock

    alt Stock insuffisant
        MM-->>OC: Stock = 0
        OC-->>F: Erreur "Menu indisponible"
        F-->>C: Formulaire avec erreur
    end

    OC->>OM: generateOrderNumber()
    OM-->>OC: "CMD-2024-00123"

    OC->>OM: create(orderData)
    OM->>DB: INSERT INTO orders (...)
    DB-->>OM: Order ID
    OM-->>OC: Order

    OC->>MM: decrementStock(menuId)
    MM->>DB: UPDATE menus SET stock = stock - 1
    DB-->>MM: OK

    OC->>Stats: logOrder(orderData)
    Stats-->>OC: Logged (MongoDB)

    OC->>ES: sendOrderConfirmation(order)
    ES-->>OC: Email envoye

    OC-->>F: Redirect /order/confirmation/{id}
    F-->>C: Page confirmation avec details
```

### Calcul du prix detaille
```
Prix de base (min_personnes)     : 320 EUR
+ Personnes supplementaires      : (nb - min) x prix_extra
= Sous-total                     : XXX EUR
- Reduction 10% (si +5 pers sup) : -XX EUR
+ Frais livraison hors Bordeaux  : 5 EUR + 0.59 EUR/km
= TOTAL                          : XXX EUR
```

---

## 4. Sequence : Gestion Commande (Employe)

```mermaid
sequenceDiagram
    autonumber
    actor E as Employe
    participant L as Liste<br/>Commandes
    participant D as Detail<br/>Commande
    participant AC as AdminController
    participant OM as Order Model
    participant DB as Base de donnees
    participant ES as EmailService

    E->>L: Accede a /admin/orders
    L->>AC: GET /admin/orders
    AC->>OM: findAll()
    OM->>DB: SELECT * FROM orders ORDER BY created_at DESC
    DB-->>OM: Commandes
    OM-->>AC: Liste commandes
    AC-->>L: Affiche liste
    L-->>E: Liste avec filtres (statut, client)

    E->>L: Clique sur commande
    L->>AC: GET /admin/orders/{id}
    AC->>OM: findById(id)
    OM->>DB: SELECT * FROM orders WHERE id = ?
    DB-->>OM: Commande
    OM-->>AC: Commande avec details
    AC-->>D: Affiche detail
    D-->>E: Detail commande + actions

    E->>D: Change statut vers "acceptee"
    D->>AC: POST /admin/orders/{id}/status

    AC->>OM: updateStatus(id, "acceptee")
    OM->>DB: UPDATE orders SET status = 'acceptee'
    DB-->>OM: OK
    OM-->>AC: OK

    AC->>ES: sendStatusUpdate(order)
    ES-->>AC: Email client envoye

    AC-->>D: Refresh page
    D-->>E: Nouveau statut affiche

    Note over E,ES: Flux similaire pour :<br/>preparation -> livraison -> livree -> terminee
```

### Etats de commande
```
nouvelle ──► acceptee ──► preparation ──► livraison ──► livree ──► terminee
                                                           │
                                                           ▼
                                                    attente_retour
                                                    (si materiel)
```

---

## 5. Sequence : Depot d'Avis

```mermaid
sequenceDiagram
    autonumber
    actor C as Client
    participant H as Historique
    participant F as Formulaire<br/>Avis
    participant RC as ReviewController
    participant OM as Order Model
    participant RM as Review Model
    participant DB as Base de donnees

    C->>H: Accede a /orders/history
    H-->>C: Liste commandes

    C->>H: Clique "Laisser un avis"<br/>(commande livree)

    H->>RC: GET /review/create/{orderId}
    RC->>OM: findById(orderId)
    OM->>DB: SELECT * FROM orders
    DB-->>OM: Commande
    OM-->>RC: Commande

    alt Commande non livree
        RC-->>H: Erreur "Commande non livree"
        H-->>C: Message erreur
    end

    RC->>RM: existsForOrder(orderId)
    RM->>DB: SELECT * FROM reviews WHERE order_id = ?
    DB-->>RM: Avis ou null

    alt Avis deja depose
        RM-->>RC: Avis existe
        RC-->>H: Erreur "Avis deja depose"
        H-->>C: Message erreur
    end

    RC-->>F: Affiche formulaire
    F-->>C: Formulaire (note 1-5, commentaire)

    C->>F: Remplit avis<br/>(5 etoiles, "Excellent !")
    F->>RC: POST /review/store

    RC->>RM: create(reviewData)
    RM->>DB: INSERT INTO reviews (..., is_approved = FALSE)
    DB-->>RM: Review ID
    RM-->>RC: Review (en attente)

    RC-->>F: Redirect /orders/history
    F-->>C: "Merci ! Votre avis sera valide par notre equipe"
```

---

## 6. Sequence : Validation Avis (Employe)

```mermaid
sequenceDiagram
    autonumber
    actor E as Employe
    participant L as Liste Avis
    participant AC as AdminController
    participant RM as Review Model
    participant DB as Base de donnees

    E->>L: Accede a /admin/reviews
    L->>AC: GET /admin/reviews
    AC->>RM: findPendingApproval()
    RM->>DB: SELECT * FROM reviews WHERE is_approved = FALSE
    DB-->>RM: Avis en attente
    RM-->>AC: Liste avis
    AC-->>L: Affiche avis a valider
    L-->>E: Liste avec note, commentaire, client

    alt Approuver avis
        E->>L: Clique "Approuver"
        L->>AC: POST /admin/reviews/{id}/approve
        AC->>RM: approve(id, employeeId)
        RM->>DB: UPDATE reviews SET is_approved = TRUE, approved_by = ?
        DB-->>RM: OK
        AC-->>L: Refresh liste
        L-->>E: Avis retire de la liste
    else Refuser avis
        E->>L: Clique "Refuser"
        L->>AC: POST /admin/reviews/{id}/reject
        AC->>RM: reject(id)
        RM->>DB: DELETE FROM reviews WHERE id = ?
        DB-->>RM: OK
        AC-->>L: Refresh liste
        L-->>E: Avis supprime
    end
```

---

## 7. Sequence : Consultation Statistiques (Admin)

```mermaid
sequenceDiagram
    autonumber
    actor A as Admin
    participant P as Page Stats
    participant AC as AdminController
    participant Stats as Statistics<br/>(MongoDB)
    participant OM as Order Model
    participant DB as MySQL

    A->>P: Accede a /admin/stats
    P->>AC: GET /admin/stats

    par Requetes paralleles
        AC->>Stats: getOrdersByMenu()
        Stats-->>AC: {menu: count}

        AC->>Stats: getMonthlyStats()
        Stats-->>AC: {month: revenue}

        AC->>OM: getTotalRevenue()
        OM->>DB: SELECT SUM(total_price) FROM orders
        DB-->>OM: Total
        OM-->>AC: CA total
    end

    AC-->>P: Donnees pour graphiques
    P-->>A: Dashboard avec :<br/>- Graphique commandes/menu<br/>- Graphique CA mensuel<br/>- KPIs

    A->>P: Applique filtres<br/>(date debut, date fin, menu)
    P->>AC: GET /admin/stats?from=X&to=Y&menu=Z

    AC->>Stats: getRevenueByMenu(filters)
    Stats-->>AC: Donnees filtrees
    AC-->>P: Mise a jour graphiques
    P-->>A: Graphiques actualises
```

---

## Export des diagrammes

### Mermaid Live Editor
1. Accede a https://mermaid.live
2. Colle chaque diagramme separement
3. Exporte en PNG ou SVG
4. Nomme les fichiers :
   - `sequence-inscription.png`
   - `sequence-connexion.png`
   - `sequence-commande.png`
   - `sequence-gestion-commande.png`
   - `sequence-avis.png`
   - `sequence-validation-avis.png`
   - `sequence-statistiques.png`
