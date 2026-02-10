# Guide de Creation des Mockups - Figma

## Difference Wireframe vs Mockup

| Wireframe | Mockup |
|-----------|--------|
| Structure et disposition | Design final |
| Niveaux de gris | Couleurs reelles |
| Placeholder texte | Vrai contenu |
| Sans images | Vraies images |
| Focus sur l'UX | Focus sur l'UI |

---

## Palette de Couleurs a Appliquer

### Couleurs principales
```css
--vert-primaire: #2E7D32;     /* Titres, boutons principaux */
--orange-secondaire: #FF8F00;  /* Prix, accents, CTA */
--bleu-accent: #1565C0;        /* Liens, infos */
```

### Couleurs neutres
```css
--texte-principal: #333333;
--texte-secondaire: #666666;
--fond-clair: #F5F5F5;
--fond-blanc: #FFFFFF;
```

### Couleurs fonctionnelles
```css
--succes: #4CAF50;
--erreur: #F44336;
--attention: #FF9800;
--info: #2196F3;
```

---

## Typographies

### Google Fonts a importer
```
Playfair Display: 400, 600, 700
Open Sans: 300, 400, 600, 700
```

### Application
- **Titres (h1, h2, h3)** : Playfair Display
- **Corps de texte** : Open Sans
- **Boutons** : Open Sans SemiBold

---

## MOCKUP 1 : PAGE ACCUEIL

### Elements visuels a ajouter

#### Header
- Logo "Vite & Gourmand" avec couleurs de la charte
- Navigation en vert (#2E7D32)
- Bouton Connexion style outline vert

#### Hero Section
- Image de fond : table de repas de fete elegante
- Overlay gradient sombre pour lisibilite
- Titre en blanc, police Playfair Display
- Bouton CTA orange (#FF8F00)

#### Section "Pourquoi nous choisir"
- Fond gris clair (#F5F5F5)
- 3 cards blanches avec ombre
- Icones Font Awesome en vert
- Titres en Playfair Display vert

#### Section Avis
- Fond blanc
- Cards avec bordure subtile
- Etoiles en orange (#FF8F00)
- Avatar rond avec bordure verte

#### Footer
- Fond vert fonce (#1B5E20)
- Texte blanc
- Icones reseaux sociaux
- Liens en hover orange

### Images suggerees (Unsplash)
- Hero : "elegant dinner table" ou "catering food"
- Cards : icones minimalistes
- Avis : photos profil diverses

---

## MOCKUP 2 : LISTE DES MENUS

### Elements visuels a ajouter

#### Filtres (sidebar desktop)
- Fond blanc avec bordure
- Checkbox stylises en vert
- Slider de prix avec poignee verte
- Bouton "Appliquer" vert plein

#### Cards Menu
- Image appetissante du plat principal
- Badge theme colore (Noel = rouge, Paques = violet, etc.)
- Titre en Playfair Display vert
- Prix en orange, bien visible
- Bouton "Voir le menu" outline vert

#### Badges themes
```
Noel:       fond #C62828, texte blanc
Paques:     fond #7B1FA2, texte blanc
Classique:  fond #2E7D32, texte blanc
Evenement:  fond #1565C0, texte blanc
Saisonnier: fond #FF8F00, texte blanc
```

#### Badges regimes
```
Classique:   fond #E0E0E0, texte #333
Vegetarien:  fond #81C784, texte blanc
Vegan:       fond #4CAF50, texte blanc
```

### Images suggerees
- Menus : "gourmet plating", "fine dining", "catering spread"
- Varier selon les themes (festif, printanier, etc.)

---

## MOCKUP 3 : DETAIL D'UN MENU

### Elements visuels a ajouter

#### Galerie d'images
- Image principale grande
- Miniatures en dessous avec bordure active
- Effet zoom au survol

#### Informations menu
- Titre en grand, Playfair Display vert
- Badges theme et regime colores
- Prix en orange, taille XXL
- Description en Open Sans gris

#### Allergenes
- Badges avec icones
- Couleur selon gravite :
  - Communs : fond jaune clair
  - Critiques : fond orange

#### Selecteur de commande
- Box avec bordure verte
- Boutons +/- stylises
- Prix dynamique en orange
- Bouton Commander vert plein, grand

#### Composition du menu
- Cards par categorie (Entree/Plat/Dessert)
- Image du plat
- Nom en vert
- Petits badges allergenes

#### Section avis
- Note moyenne grande en orange
- Etoiles pleines/vides
- Avatar + nom + date
- Commentaire en italique

### Images suggerees
- Galerie : plusieurs angles du menu
- Plats : "foie gras", "roast turkey", "yule log"
- Avatars : portraits varies

---

## Composants Figma a Creer

### 1. Boutons
```
Primary:     fond #2E7D32, texte blanc, hover #1B5E20
Secondary:   fond #FF8F00, texte blanc, hover #E65100
Outline:     bordure #2E7D32, texte #2E7D32, hover fond vert clair
Disabled:    fond #BDBDBD, texte #757575
```

### 2. Cards
```
- Border-radius: 10px
- Box-shadow: 0 4px 15px rgba(0,0,0,0.1)
- Padding: 0 (image) + 20px (contenu)
- Hover: translateY(-5px), shadow plus forte
```

### 3. Inputs
```
- Border: 1px solid #BDBDBD
- Border-radius: 5px
- Padding: 12px 16px
- Focus: border-color #2E7D32, box-shadow verte
```

### 4. Badges
```
- Border-radius: 20px
- Padding: 4px 12px
- Font-size: 12px
- Font-weight: 600
```

### 5. Navigation
```
Desktop:
- Liens espaces de 32px
- Hover: couleur orange
- Active: underline vert

Mobile:
- Menu hamburger
- Drawer lateral gauche
- Animation slide-in
```

---

## Checklist Export Mockups

### Format d'export
- PNG 2x (pour qualite retina)
- Ou PDF pour le dossier

### Nommage
```
mockup-accueil-mobile.png
mockup-accueil-desktop.png
mockup-menus-mobile.png
mockup-menus-desktop.png
mockup-menu-detail-mobile.png
mockup-menu-detail-desktop.png
```

### Verification finale
- [ ] Couleurs conformes a la charte
- [ ] Typographies correctes
- [ ] Images de qualite
- [ ] Coherence entre les pages
- [ ] Responsive (mobile vs desktop)
- [ ] Contrastes accessibles (RGAA)

---

## Ressources Images Gratuites

### Sites recommandes
1. **Unsplash** - unsplash.com
   - Recherche: "catering", "dinner party", "gourmet food"

2. **Pexels** - pexels.com
   - Recherche: "fine dining", "buffet", "celebration dinner"

3. **Foodiesfeed** - foodiesfeed.com
   - Photos de nourriture haute qualite

### Avatars
- **UI Faces** - uifaces.co
- **Generated Photos** - generated.photos

---

## Conseils Pro

### Coherence visuelle
- Utilise les memes espacements partout
- Garde les memes styles de boutons
- Applique la meme ombre sur toutes les cards

### Accessibilite
- Contraste minimum 4.5:1 pour le texte
- Ne pas utiliser la couleur seule pour transmettre l'info
- Texte alternatif prevu pour les images

### Presentation
- Ajoute un fond neutre derriere tes mockups
- Presente mobile et desktop cote a cote
- Ajoute des annotations si necessaire

---

## Template de Presentation

Pour ton dossier d'examen, presente les mockups ainsi :

```
Page 1: Titre "Mockups - Vite & Gourmand"

Page 2: Accueil
        [Desktop]  |  [Mobile]

Page 3: Liste des menus
        [Desktop]  |  [Mobile]

Page 4: Detail menu
        [Desktop]  |  [Mobile]
```

Exporte en PDF pour une presentation professionnelle.
