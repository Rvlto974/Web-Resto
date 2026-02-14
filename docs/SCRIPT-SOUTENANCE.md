# Script de Soutenance DWWM - Vite & Gourmand

> **Duree totale** : 45 minutes
> **Candidat** : Mathieu Jacquet
> **Projet** : Application web de commande en ligne pour traiteur

---

## INTRODUCTION (5 minutes)

### Slide 1 : Presentation

> "Bonjour, je suis Mathieu Jacquet et je vais vous presenter mon projet de fin de formation : **Vite & Gourmand**, une application web de commande en ligne pour un traiteur."

### Slide 2 : Contexte

> "Vite & Gourmand est une entreprise de traiteur bordelaise avec 25 ans d'experience, dirigee par Julie et Jose. Ils souhaitaient moderniser leur activite avec une solution de commande en ligne."

### Slide 3 : Objectifs du projet

> "Les objectifs etaient :
> 1. Permettre aux clients de consulter et commander des menus en ligne
> 2. Offrir un espace d'administration pour gerer les commandes
> 3. Proposer une experience responsive mobile-first
> 4. Garantir la securite et la conformite RGPD"

### Slide 4 : Stack technique

> "J'ai choisi les technologies suivantes :
> - **Front-end** : HTML5, CSS3, Bootstrap 5, JavaScript
> - **Back-end** : PHP 8.2 avec architecture MVC
> - **Base de donnees** : MySQL 8
> - **Paiement** : Stripe
> - **Deploiement** : Docker et Fly.io"

---

## DEMONSTRATION (25 minutes)

### Partie 1 : Parcours VISITEUR (5 min)

#### 1.1 Page d'accueil
> "Voici la page d'accueil du site. On retrouve :
> - Le header avec navigation responsive
> - Une section hero avec appel a l'action
> - Les points forts de l'entreprise
> - Les temoignages clients"

**Action** : Montrer le site, scroller, montrer le footer

#### 1.2 Liste des menus
> "Sur la page des menus, le visiteur peut :
> - Voir tous les menus disponibles
> - Filtrer par theme (Noel, Paques, etc.)
> - Filtrer par regime alimentaire (vegetarien, vegan)
> - Filtrer par prix et nombre de personnes"

**Action** : Cliquer sur `/menu`, utiliser les filtres AJAX

#### 1.3 Detail d'un menu
> "En cliquant sur un menu, on accede au detail avec :
> - La galerie photos
> - La description complete
> - Les allergenes avec icones
> - Le calculateur de prix dynamique
> - Les avis des clients"

**Action** : Cliquer sur "Menu Noel Premium", montrer les allergenes, le calcul prix

#### 1.4 Formulaire de contact
> "Le visiteur peut contacter l'entreprise via un formulaire securise avec protection anti-spam."

**Action** : Montrer `/contact` rapidement

---

### Partie 2 : Parcours CLIENT (10 min)

#### 2.1 Inscription
> "Pour commander, le visiteur doit creer un compte. Le formulaire valide :
> - L'email unique
> - Le mot de passe securise (10 caracteres, majuscule, minuscule, chiffre, special)
> - L'acceptation des CGV et politique de confidentialite"

**Action** : Montrer `/user/register`, pointer les validations

#### 2.2 Connexion
> "Je me connecte avec un compte client existant."

**Action** : Se connecter avec `client@test.fr` / `password`

#### 2.3 Ajout au panier
> "Le client peut ajouter plusieurs menus au panier avec des quantites differentes."

**Action** : Aller sur un menu, choisir 10 personnes, ajouter au panier

#### 2.4 Gestion du panier
> "Le panier permet de :
> - Modifier les quantites
> - Supprimer des articles
> - Voir le total avec frais de livraison
> - Beneficier de reductions (10% au-dela de 5 personnes supplementaires)"

**Action** : Montrer `/cart`, modifier une quantite

#### 2.5 Commande et paiement
> "Le client peut finaliser sa commande avec :
> - Ses informations de livraison
> - Le choix de la date et heure
> - Le paiement par carte via Stripe ou a la livraison"

**Action** : Montrer le checkout (sans finaliser le paiement)

#### 2.6 Historique des commandes
> "Le client peut suivre toutes ses commandes et leur statut."

**Action** : Aller sur `/order/history`

#### 2.7 Laisser un avis
> "Apres livraison, le client peut laisser un avis avec une note et un commentaire."

**Action** : Montrer le formulaire d'avis sur un menu

#### 2.8 RGPD - Export des donnees
> "Conformement au RGPD, le client peut telecharger toutes ses donnees personnelles au format JSON."

**Action** : Aller sur `/user/profile`, montrer le bouton "Telecharger mes donnees"

#### 2.9 RGPD - Suppression du compte
> "Le client peut aussi supprimer son compte. Les commandes sont conservees de maniere anonymisee pour la comptabilite."

**Action** : Montrer `/user/deleteAccount` (sans executer)

---

### Partie 3 : Parcours EMPLOYE (7 min)

#### 3.1 Connexion admin
> "Je me connecte maintenant en tant qu'employe."

**Action** : Se deconnecter, se connecter avec `employe@viteetgourmand.fr` / `password`

#### 3.2 Dashboard
> "Le tableau de bord affiche :
> - Le nombre de commandes par statut
> - Le chiffre d'affaires total
> - Les commandes recentes
> - Les avis en attente de moderation"

**Action** : Montrer `/admin/dashboard`

#### 3.3 Gestion des menus (CRUD)
> "L'employe peut creer, modifier et supprimer des menus."

**Action** :
- Montrer la liste `/admin/menus`
- Cliquer sur "Modifier" un menu
- Montrer le formulaire avec upload image

#### 3.4 Gestion des commandes
> "L'employe gere le cycle de vie des commandes :
> - En attente → Acceptee → En preparation → En livraison → Livree → Terminee"

**Action** :
- Aller sur `/admin/orders`
- Ouvrir une commande
- Changer le statut

#### 3.5 Moderation des avis
> "Les avis clients doivent etre approuves avant publication."

**Action** :
- Aller sur `/admin/reviews`
- Montrer les boutons Approuver/Supprimer

---

### Partie 4 : Parcours ADMIN (3 min)

#### 4.1 Connexion admin
> "L'administrateur a des droits supplementaires."

**Action** : Se connecter avec `admin@viteetgourmand.fr` / `password`

#### 4.2 Gestion des employes
> "L'admin peut creer des comptes employes avec un mot de passe temporaire."

**Action** : Montrer `/admin/employees`

#### 4.3 Statistiques
> "L'admin accede aux statistiques detaillees : commandes, revenus, utilisateurs."

**Action** : Montrer `/admin/stats`

---

## PRESENTATION TECHNIQUE (10 minutes)

### 5.1 Architecture MVC

> "Le projet suit une architecture MVC classique :"

**Action** : Ouvrir le code et montrer :

```
src/
├── Controllers/     → Logique metier
├── Models/          → Acces base de donnees
├── Views/           → Templates PHP
└── Core/            → Classes utilitaires
```

> "Par exemple, pour afficher un menu :
> 1. Le Router analyse l'URL `/menu/show/1`
> 2. Il appelle `MenuController->show(1)`
> 3. Le Controller utilise `Menu::findById(1)`
> 4. Le Model execute la requete SQL
> 5. Le Controller passe les donnees a la View"

**Action** : Montrer `MenuController.php` → `Menu.php` → `menu/show.php`

### 5.2 Securite - Protection CSRF

> "Tous les formulaires sont proteges contre les attaques CSRF."

**Action** : Ouvrir `src/Core/Csrf.php`

> "Un token unique est genere par session et verifie a chaque soumission POST."

**Action** : Montrer un formulaire avec `<?php echo Csrf::getInputField(); ?>`

### 5.3 Securite - Injection SQL

> "Toutes les requetes utilisent des requetes preparees PDO."

**Action** : Ouvrir `src/Views/core/Model.php`

```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
```

> "Les parametres sont separes de la requete, impossible d'injecter du SQL."

### 5.4 Securite - XSS

> "Toutes les donnees affichees sont echappees."

**Action** : Montrer une vue avec `htmlspecialchars()`

```php
<?php echo htmlspecialchars($user['first_name']); ?>
```

### 5.5 Authentification

> "L'authentification utilise des sessions PHP securisees."

**Action** : Ouvrir `src/Core/Auth.php`

> "Les mots de passe sont haches avec bcrypt, et l'ID de session est regenere a la connexion pour eviter le session fixation."

### 5.6 Responsive Design

> "Le site est responsive grace a Bootstrap 5."

**Action** : Ouvrir les DevTools, passer en mode mobile, naviguer sur le site

---

## CONCLUSION (2 minutes)

### Points forts du projet

> "Pour conclure, les points forts de ce projet sont :
>
> 1. **Architecture solide** : MVC bien structure, code maintenable
> 2. **Securite** : CSRF, XSS, SQL injection, authentification robuste
> 3. **Conformite RGPD** : Export et suppression des donnees
> 4. **Accessibilite** : Skip links, ARIA, focus visible
> 5. **Documentation complete** : UML, wireframes, mockups, dossier technique
> 6. **Deploiement** : Docker et Fly.io fonctionnels"

### Axes d'amelioration

> "Les axes d'amelioration seraient :
> - Ajouter l'envoi d'emails (confirmation, notifications)
> - Implementer les statistiques MongoDB
> - Ajouter des tests unitaires PHPUnit"

### Mot de fin

> "Je vous remercie pour votre attention et je suis pret a repondre a vos questions."

---

## QUESTIONS FREQUENTES

### Architecture

**Q : Pourquoi avoir choisi PHP plutot qu'un framework comme Laravel ?**
> "Pour ce projet, j'ai voulu montrer ma comprehension des fondamentaux MVC sans m'appuyer sur un framework. Cela demontre ma capacite a construire une architecture from scratch."

**Q : Comment fonctionne votre routeur ?**
> "Le routeur analyse l'URL, extrait le nom du controller et de la methode, puis les appelle dynamiquement avec les parametres."

### Securite

**Q : Qu'est-ce qu'une attaque CSRF ?**
> "Cross-Site Request Forgery : un attaquant fait executer une action a un utilisateur connecte sans son consentement. Le token CSRF empeche cela en verifiant que la requete vient bien de notre formulaire."

**Q : Comment stockez-vous les mots de passe ?**
> "Avec `password_hash()` qui utilise bcrypt. Le mot de passe n'est jamais stocke en clair."

### Base de donnees

**Q : Pourquoi MySQL plutot que PostgreSQL ou MongoDB ?**
> "MySQL est tres repandu, performant, et parfaitement adapte aux donnees relationnelles de ce projet (utilisateurs, commandes, menus)."

**Q : Comment gerez-vous les relations entre tables ?**
> "Avec des cles etrangeres et des jointures SQL. Par exemple, une commande reference un utilisateur et un menu."

### RGPD

**Q : Que se passe-t-il quand un utilisateur supprime son compte ?**
> "Les donnees personnelles sont anonymisees, mais les commandes sont conservees 10 ans pour la comptabilite, conformement a la loi."

### Deploiement

**Q : Pourquoi Docker ?**
> "Docker garantit que l'environnement est identique en developpement et en production. Pas de 'ca marche sur ma machine'."

**Q : Comment avez-vous deploye en production ?**
> "J'utilise Fly.io, un PaaS qui deploie automatiquement depuis le Dockerfile. La base de donnees est hebergee sur Railway."

---

## CHECKLIST JOUR J

### La veille
- [ ] Verifier que Docker fonctionne
- [ ] Verifier le site en production
- [ ] Imprimer les documents
- [ ] Preparer la cle USB de secours

### Le matin
- [ ] Arriver 15 min en avance
- [ ] Demarrer Docker (`docker-compose up -d`)
- [ ] Ouvrir le navigateur avec les onglets :
  - Site local : http://localhost:8080
  - Site production : https://vite-gourmand-resto.fly.dev
  - Admin local : http://localhost:8080/admin
  - GitHub : https://github.com/Rvlto974/Web-Resto
- [ ] Ouvrir VS Code avec le projet
- [ ] Tester une connexion rapide

### Comptes a retenir
| Role | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@viteetgourmand.fr | password |
| Employe | employe@viteetgourmand.fr | password |
| Client | client@test.fr | password |

---

**Bonne soutenance !** 🎓
