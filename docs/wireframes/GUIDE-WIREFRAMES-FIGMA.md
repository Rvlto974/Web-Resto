# Guide de Creation des Wireframes - Figma

## Introduction

Ce guide te permettra de creer les **6 wireframes** requis pour le projet Vite & Gourmand :
- 3 wireframes **mobile** (375px de large)
- 3 wireframes **desktop** (1440px de large)

## Pages a wireframer

| # | Page | Mobile | Desktop |
|---|------|--------|---------|
| 1 | Accueil | `accueil-mobile.png` | `accueil-desktop.png` |
| 2 | Liste des menus | `menus-mobile.png` | `menus-desktop.png` |
| 3 | Detail d'un menu | `menu-detail-mobile.png` | `menu-detail-desktop.png` |

---

## Configuration Figma

### Etape 1 : Creer un nouveau projet
1. Va sur [figma.com](https://www.figma.com) et connecte-toi
2. Clique sur **"New design file"**
3. Renomme le fichier : `Vite-et-Gourmand-Wireframes`

### Etape 2 : Creer les frames
Pour chaque page, cree 2 frames :

**Mobile (iPhone 14):**
- Largeur : 375px
- Hauteur : Auto (selon contenu)

**Desktop:**
- Largeur : 1440px
- Hauteur : Auto (selon contenu)

### Etape 3 : Style wireframe
Les wireframes sont en **niveaux de gris** :
- Fond : `#FFFFFF`
- Texte : `#333333`
- Placeholder image : `#E0E0E0`
- Bordures : `#BDBDBD`
- Boutons : `#9E9E9E`

---

## WIREFRAME 1 : PAGE ACCUEIL

### Structure Desktop (1440px)

```
+------------------------------------------------------------------+
|  HEADER                                                           |
|  [Logo]     Accueil  Nos Menus  Contact     [Connexion] [Compte] |
+------------------------------------------------------------------+

+------------------------------------------------------------------+
|                         HERO SECTION                              |
|                                                                   |
|     [       IMAGE DE FOND (placeholder gris)      ]               |
|                                                                   |
|              "Vite & Gourmand"                                    |
|         Traiteur d'exception depuis 25 ans                        |
|                                                                   |
|              [ Decouvrir nos menus ]                              |
|                                                                   |
+------------------------------------------------------------------+

+------------------------------------------------------------------+
|                    POURQUOI NOUS CHOISIR                          |
|                                                                   |
|   +----------------+  +----------------+  +----------------+      |
|   |   [icone]      |  |   [icone]      |  |   [icone]      |      |
|   |                |  |                |  |                |      |
|   | 25 ans         |  | Produits       |  | Livraison      |      |
|   | d'experience   |  | locaux         |  | Bordeaux       |      |
|   |                |  |                |  |                |      |
|   | Description... |  | Description... |  | Description... |      |
|   +----------------+  +----------------+  +----------------+      |
|                                                                   |
+------------------------------------------------------------------+

+------------------------------------------------------------------+
|                       AVIS CLIENTS                                |
|                                                                   |
|   <  +--------------------------------------------------+  >     |
|      |  [Avatar]  "Commentaire du client..."    *****   |        |
|      |            - Nom du client                       |        |
|      +--------------------------------------------------+        |
|                                                                   |
|                         o  o  o  (pagination)                     |
+------------------------------------------------------------------+

+------------------------------------------------------------------+
|  FOOTER                                                           |
|                                                                   |
|  Vite & Gourmand        Horaires           Contact                |
|  Traiteur Bordeaux      Lun-Ven: 9h-18h    04 XX XX XX XX        |
|                         Sam: 9h-12h        contact@viteetgourmand |
|  [FB] [Insta]                              Bordeaux, France       |
|                                                                   |
|  Mentions legales | CGV | Politique de confidentialite           |
+------------------------------------------------------------------+
```

### Structure Mobile (375px)

```
+------------------------+
| [=] Logo    [Connexion]|
+------------------------+

+------------------------+
|                        |
|   [ IMAGE HERO ]       |
|                        |
|  "Vite & Gourmand"     |
|  Traiteur depuis 25ans |
|                        |
| [Decouvrir nos menus]  |
|                        |
+------------------------+

+------------------------+
| POURQUOI NOUS CHOISIR  |
+------------------------+
|  +------------------+  |
|  |    [icone]       |  |
|  | 25 ans exp.      |  |
|  | Description...   |  |
|  +------------------+  |
|                        |
|  +------------------+  |
|  |    [icone]       |  |
|  | Produits locaux  |  |
|  | Description...   |  |
|  +------------------+  |
|                        |
|  +------------------+  |
|  |    [icone]       |  |
|  | Livraison        |  |
|  | Description...   |  |
|  +------------------+  |
+------------------------+

+------------------------+
|     AVIS CLIENTS       |
+------------------------+
|  +------------------+  |
|  | [Avatar]         |  |
|  | "Commentaire..." |  |
|  | ***** - Nom      |  |
|  +------------------+  |
|       < o o o >        |
+------------------------+

+------------------------+
| FOOTER                 |
| Vite & Gourmand        |
| Bordeaux               |
|                        |
| Horaires: Lun-Ven 9-18 |
| Tel: 04 XX XX XX XX    |
|                        |
| [FB] [Insta]           |
|                        |
| Mentions | CGV | RGPD  |
+------------------------+
```

### Elements a inclure
- [ ] Header avec logo et navigation
- [ ] Hero section avec image, titre, slogan, CTA
- [ ] Section "Pourquoi nous choisir" (3 cards)
- [ ] Section avis clients (carousel)
- [ ] Footer avec horaires, contact, liens legaux

---

## WIREFRAME 2 : LISTE DES MENUS

### Structure Desktop (1440px)

```
+------------------------------------------------------------------+
|  HEADER (identique)                                               |
+------------------------------------------------------------------+

+------------------------------------------------------------------+
|  Fil d'ariane: Accueil > Nos Menus                                |
+------------------------------------------------------------------+

+------------------------------------------------------------------+
|                                                                   |
|  +-------------+    +------------------------------------------+  |
|  | FILTRES     |    |  NOS MENUS           Trier par: [Prix v] |  |
|  +-------------+    +------------------------------------------+  |
|  |             |    |                                          |  |
|  | Prix max    |    |  +----------+  +----------+  +----------+|  |
|  | [____] EUR  |    |  |  [IMG]   |  |  [IMG]   |  |  [IMG]   ||  |
|  |             |    |  |          |  |          |  |          ||  |
|  | Theme       |    |  | Menu 1   |  | Menu 2   |  | Menu 3   ||  |
|  | [ ] Noel    |    |  | Theme    |  | Theme    |  | Theme    ||  |
|  | [ ] Paques  |    |  | 8 pers.  |  | 10 pers. |  | 6 pers.  ||  |
|  | [ ] Classic |    |  | 320 EUR  |  | 280 EUR  |  | 250 EUR  ||  |
|  | [ ] Event   |    |  +----------+  +----------+  +----------+|  |
|  |             |    |                                          |  |
|  | Regime      |    |  +----------+  +----------+  +----------+|  |
|  | [ ] Classic |    |  |  [IMG]   |  |  [IMG]   |  |  [IMG]   ||  |
|  | [ ] Vegeta. |    |  | Menu 4   |  | Menu 5   |  | Menu 6   ||  |
|  | [ ] Vegan   |    |  | ...      |  | ...      |  | ...      ||  |
|  |             |    |  +----------+  +----------+  +----------+|  |
|  | Nb personnes|    |                                          |  |
|  | Min: [__]   |    |         < 1  2  3  4  5 >                |  |
|  |             |    |                                          |  |
|  | [Appliquer] |    +------------------------------------------+  |
|  +-------------+                                                  |
|                                                                   |
+------------------------------------------------------------------+

+------------------------------------------------------------------+
|  FOOTER (identique)                                               |
+------------------------------------------------------------------+
```

### Structure Mobile (375px)

```
+------------------------+
| [=] Logo    [Connexion]|
+------------------------+
| Accueil > Nos Menus    |
+------------------------+

+------------------------+
| NOS MENUS              |
| [Filtres]  [Trier v]   |
+------------------------+

+------------------------+
|  +------------------+  |
|  |    [IMAGE]       |  |
|  +------------------+  |
|  | Menu Noel Premium|  |
|  | Theme: Noel      |  |
|  | Min: 8 personnes |  |
|  | 320 EUR          |  |
|  | [Voir le menu]   |  |
|  +------------------+  |
+------------------------+

|  +------------------+  |
|  |    [IMAGE]       |  |
|  +------------------+  |
|  | Menu Printanier  |  |
|  | Theme: Classique |  |
|  | Min: 6 personnes |  |
|  | 250 EUR          |  |
|  | [Voir le menu]   |  |
|  +------------------+  |

+------------------------+
|     < 1  2  3  4  5 >  |
+------------------------+

+------------------------+
| FOOTER                 |
+------------------------+
```

### Drawer Filtres Mobile (overlay)

```
+------------------------+
| FILTRES           [X]  |
+------------------------+
|                        |
| Prix maximum           |
| [          ] EUR       |
|                        |
| Theme                  |
| [ ] Noel               |
| [ ] Paques             |
| [ ] Classique          |
| [ ] Evenement          |
|                        |
| Regime alimentaire     |
| [ ] Classique          |
| [ ] Vegetarien         |
| [ ] Vegan              |
|                        |
| Nombre de personnes    |
| Minimum: [    ]        |
|                        |
| [    Appliquer    ]    |
| [    Reinitialiser ]   |
+------------------------+
```

### Elements a inclure
- [ ] Fil d'ariane
- [ ] Sidebar filtres (desktop) / Bouton filtres (mobile)
- [ ] Grille de cards menus (3 colonnes desktop, 1 mobile)
- [ ] Tri par prix/popularite
- [ ] Pagination
- [ ] Chaque card : image, titre, theme, personnes, prix, CTA

---

## WIREFRAME 3 : DETAIL D'UN MENU

### Structure Desktop (1440px)

```
+------------------------------------------------------------------+
|  HEADER (identique)                                               |
+------------------------------------------------------------------+

+------------------------------------------------------------------+
|  Fil d'ariane: Accueil > Nos Menus > Menu Noel Premium            |
+------------------------------------------------------------------+

+------------------------------------------------------------------+
|                                                                   |
|  +---------------------------+  +------------------------------+  |
|  |                           |  |                              |  |
|  |    [  IMAGE PRINCIPALE ]  |  |  Menu Noel Premium           |  |
|  |                           |  |                              |  |
|  +---------------------------+  |  Theme: Noel  |  Classique   |  |
|                                 |                              |  |
|  [img1] [img2] [img3] [img4]    |  A partir de 320 EUR         |  |
|                                 |  Pour 8 personnes minimum    |  |
|                                 |                              |  |
|                                 |  Description du menu...      |  |
|                                 |  Lorem ipsum dolor sit amet  |  |
|                                 |                              |  |
|                                 |  Allergenes: [Gluten] [Lait] |  |
|                                 |                              |  |
|                                 |  +------------------------+  |  |
|                                 |  | Nb personnes: [8]  [+][-]|  |
|                                 |  | Prix estime: 320 EUR    |  |
|                                 |  | [ Commander ce menu ]   |  |
|                                 |  +------------------------+  |  |
|                                 |                              |  |
|                                 |  * Delai: 48h minimum        |  |
|                                 |  * Livraison disponible      |  |
|                                 +------------------------------+  |
|                                                                   |
+------------------------------------------------------------------+

+------------------------------------------------------------------+
|                        COMPOSITION DU MENU                        |
+------------------------------------------------------------------+
|                                                                   |
|  ENTREE                    PLAT                    DESSERT        |
|  +----------------+        +----------------+      +-------------+|
|  |  [image]       |        |  [image]       |      |  [image]    ||
|  |  Foie gras     |        |  Dinde marrons |      |  Buche      ||
|  |  Description   |        |  Description   |      |  chocolat   ||
|  |  Allergenes:   |        |  Allergenes:   |      |  Allergenes ||
|  |  [Gluten]      |        |  [Oeuf]        |      |  [Lait]     ||
|  +----------------+        +----------------+      +-------------+|
|                                                                   |
+------------------------------------------------------------------+

+------------------------------------------------------------------+
|                    CONDITIONS ET INFORMATIONS                     |
+------------------------------------------------------------------+
|                                                                   |
|  Delai de commande          Conservation              Livraison   |
|  48 heures minimum          A conserver entre         5EUR + 0.59 |
|  avant la date de           0 et 4 degres C           EUR/km hors |
|  livraison souhaitee        jusqu'a consommation      Bordeaux    |
|                                                                   |
+------------------------------------------------------------------+

+------------------------------------------------------------------+
|                         AVIS SUR CE MENU                          |
+------------------------------------------------------------------+
|                                                                   |
|  ***** (4.8/5) - 24 avis                                          |
|                                                                   |
|  +------------------------------------------------------------+  |
|  | [Avatar] Marie D.        *****            Il y a 2 semaines|  |
|  | "Excellent menu pour notre repas de Noel en famille..."    |  |
|  +------------------------------------------------------------+  |
|                                                                   |
|  +------------------------------------------------------------+  |
|  | [Avatar] Pierre L.       ****             Il y a 1 mois    |  |
|  | "Tres bon rapport qualite prix, livraison impeccable"      |  |
|  +------------------------------------------------------------+  |
|                                                                   |
|  [Voir tous les avis]                                             |
|                                                                   |
+------------------------------------------------------------------+

+------------------------------------------------------------------+
|  FOOTER (identique)                                               |
+------------------------------------------------------------------+
```

### Structure Mobile (375px)

```
+------------------------+
| [=] Logo    [Connexion]|
+------------------------+
| < Retour aux menus     |
+------------------------+

+------------------------+
|                        |
|   [IMAGE PRINCIPALE]   |
|                        |
+------------------------+
| [o] [o] [o] [o]        |
+------------------------+

+------------------------+
| Menu Noel Premium      |
|                        |
| Theme: Noel | Classique|
|                        |
| A partir de 320 EUR    |
| Pour 8 personnes min.  |
+------------------------+

+------------------------+
| Description du menu... |
| Lorem ipsum dolor sit  |
| amet consectetur...    |
+------------------------+

+------------------------+
| Allergenes presents:   |
| [Gluten] [Lait] [Oeuf] |
+------------------------+

+------------------------+
| +--------------------+ |
| | Nb pers: [-] 8 [+] | |
| | Prix: 320 EUR      | |
| | [Commander]        | |
| +--------------------+ |
+------------------------+

+------------------------+
| COMPOSITION            |
+------------------------+
| ENTREE                 |
| +------------------+   |
| | [img] Foie gras  |   |
| | Description...   |   |
| | [Gluten]         |   |
| +------------------+   |
|                        |
| PLAT                   |
| +------------------+   |
| | [img] Dinde      |   |
| | Description...   |   |
| | [Oeuf]           |   |
| +------------------+   |
|                        |
| DESSERT                |
| +------------------+   |
| | [img] Buche      |   |
| | Description...   |   |
| | [Lait]           |   |
| +------------------+   |
+------------------------+

+------------------------+
| CONDITIONS             |
+------------------------+
| Delai: 48h minimum     |
| Conservation: 0-4°C    |
| Livraison: 5EUR + 0.59 |
| EUR/km hors Bordeaux   |
+------------------------+

+------------------------+
| AVIS ***** 4.8/5       |
+------------------------+
| [Avatar] Marie D.      |
| ***** - 2 semaines     |
| "Excellent menu..."    |
+------------------------+
| [Avatar] Pierre L.     |
| **** - 1 mois          |
| "Tres bon rapport..."  |
+------------------------+
| [Voir tous les avis]   |
+------------------------+

+------------------------+
| FOOTER                 |
+------------------------+
```

### Elements a inclure
- [ ] Galerie d'images avec miniatures
- [ ] Titre, theme, regime alimentaire
- [ ] Prix et nombre de personnes
- [ ] Description complete
- [ ] Liste des allergenes (badges)
- [ ] Selecteur de quantite + estimation prix
- [ ] Bouton Commander (lie a connexion)
- [ ] Composition du menu (entree, plat, dessert)
- [ ] Conditions (delai, conservation, livraison)
- [ ] Section avis avec notes

---

## Conseils Figma

### Plugins utiles
- **Wireframe** : Kit de composants wireframe
- **Unsplash** : Images placeholder
- **Iconify** : Icones pour wireframes

### Raccourcis utiles
- `F` : Creer une frame
- `R` : Rectangle
- `T` : Texte
- `Ctrl+D` : Dupliquer
- `Ctrl+G` : Grouper

### Organisation
1. Cree une page par type (Mobile / Desktop)
2. Nomme clairement tes frames
3. Utilise des composants pour le header/footer
4. Garde une structure coherente

---

## Export

### Pour le dossier d'examen
1. Selectionne chaque frame
2. Exporte en PNG (2x pour la qualite)
3. Ou exporte toute la page en PDF

### Nommage des fichiers
```
wireframe-accueil-mobile.png
wireframe-accueil-desktop.png
wireframe-menus-mobile.png
wireframe-menus-desktop.png
wireframe-menu-detail-mobile.png
wireframe-menu-detail-desktop.png
```

---

## Checklist finale

### Wireframes Mobile (375px)
- [ ] Accueil mobile
- [ ] Liste menus mobile
- [ ] Detail menu mobile

### Wireframes Desktop (1440px)
- [ ] Accueil desktop
- [ ] Liste menus desktop
- [ ] Detail menu desktop

### Verification
- [ ] Navigation coherente
- [ ] Tous les elements requis presents
- [ ] Lisibilite du texte
- [ ] Hierarchie visuelle claire
- [ ] Export en PNG ou PDF
