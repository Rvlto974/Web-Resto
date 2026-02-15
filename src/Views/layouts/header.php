<?php
require_once __DIR__ . '/../../Core/Auth.php';
require_once __DIR__ . '/../../Models/Cart.php';
Auth::init();
$currentUser = Auth::user();
$flashMessages = Auth::getAllFlash();
$cartCount = Cart::count();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . ' - ' : ''; ?>Vite & Gourmand</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="apple-touch-icon" href="/favicon.svg">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --vert-primaire: #5DA99A;
            --vert-fonce: #3D7A6E;
            --orange: #FF8F00;
            --orange-hover: #E65100;
            --texte: #333333;
            --texte-light: #666666;
            --fond-gris: #F5F5F5;
            --blanc: #FFFFFF;
            /* Variables pour style.css */
            --color-primary: #5DA99A;
            --color-primary-dark: #3D7A6E;
            --color-primary-light: #7DBFB2;
            --color-secondary: #FF8F00;
            --color-secondary-dark: #E65100;
            --color-white: #FFFFFF;
            --color-text: #333333;
            --color-text-light: #666666;
            --color-bg-light: #F5F5F5;
            --font-heading: 'Playfair Display', serif;
            --font-body: 'Open Sans', sans-serif;
        }
        body {
            font-family: 'Open Sans', sans-serif;
            background: var(--blanc);
            color: var(--texte);
        }
        h1, h2, h3, h4 { font-family: 'Playfair Display', serif; }
        a { text-decoration: none; }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 60px;
            background: var(--blanc);
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }
        .logo {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 28px;
            color: var(--vert-primaire);
            text-decoration: none;
        }
        .nav { display: flex; gap: 40px; }
        .nav-link {
            color: var(--texte);
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            transition: color 0.3s;
        }
        .nav-link:hover { color: var(--vert-primaire); }
        .header-actions { display: flex; gap: 15px; align-items: center; }
        .btn-outline {
            border: 2px solid var(--vert-primaire);
            color: var(--vert-primaire);
            padding: 12px 25px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-outline:hover {
            background: var(--vert-primaire);
            color: var(--blanc);
        }
        .btn-primary-header {
            background: var(--vert-primaire);
            color: var(--blanc);
            padding: 12px 25px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
            border: none;
            cursor: pointer;
        }
        .btn-primary-header:hover { background: var(--vert-fonce); color: var(--blanc); }

        /* Dropdown */
        .dropdown-custom { position: relative; }
        .dropdown-menu-custom {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            background: var(--blanc);
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            min-width: 200px;
            padding: 10px 0;
            z-index: 1000;
        }
        .dropdown-menu-custom.show { display: block; }
        .dropdown-menu-custom a {
            display: block;
            padding: 12px 20px;
            color: var(--texte);
            text-decoration: none;
        }
        .dropdown-menu-custom a:hover { background: var(--fond-gris); }
        .dropdown-menu-custom hr { margin: 10px 0; border: none; border-top: 1px solid #eee; }

        /* Flash messages */
        .flash-container { padding: 0 60px; margin-top: 20px; }

        /* Mobile menu button */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--vert-primaire);
            cursor: pointer;
            padding: 5px;
        }

        /* Mobile navigation */
        .mobile-nav {
            display: none;
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            background: var(--blanc);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 20px;
            z-index: 999;
        }
        .mobile-nav.show { display: block; }
        .mobile-nav a {
            display: block;
            padding: 15px 20px;
            color: var(--texte);
            text-decoration: none;
            font-size: 16px;
            border-radius: 8px;
        }
        .mobile-nav a:hover { background: var(--fond-gris); }
        .mobile-nav-divider {
            height: 1px;
            background: #eee;
            margin: 10px 0;
        }

        /* Responsive header */
        @media (max-width: 992px) {
            .header {
                padding: 15px 20px;
            }
            .nav {
                display: none;
            }
            .header-actions .btn-outline,
            .header-actions .btn-primary-header,
            .header-actions .dropdown {
                display: none;
            }
            .mobile-menu-btn {
                display: block;
            }
            .flash-container {
                padding: 0 20px;
            }
        }

        @media (max-width: 576px) {
            .logo {
                font-size: 22px;
            }
            .cart-icon {
                font-size: 20px !important;
            }
        }

        /* Accessibilite - Skip link */
        .skip-link {
            position: absolute;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--vert-primaire);
            color: white;
            padding: 10px 20px;
            border-radius: 0 0 5px 5px;
            z-index: 9999;
            transition: top 0.3s;
        }
        .skip-link:focus {
            top: 0;
            outline: 3px solid var(--orange);
        }

        /* Accessibilite - Focus visible */
        a:focus, button:focus, input:focus, select:focus, textarea:focus {
            outline: 3px solid var(--orange);
            outline-offset: 2px;
        }
        .btn:focus, .btn-primary-header:focus, .btn-outline:focus {
            outline: 3px solid var(--orange);
            outline-offset: 2px;
            box-shadow: none;
        }

        /* Classe pour cacher visuellement mais garder accessible */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
    </style>
</head>
<body>
    <!-- Skip link pour accessibilite -->
    <a href="#main-content" class="visually-hidden-focusable skip-link">
        Aller au contenu principal
    </a>

    <!-- Header -->
    <header class="header" role="banner">
        <a href="/" class="logo">Vite & Gourmand</a>
        <nav class="nav" role="navigation" aria-label="Menu principal">
            <a href="/" class="nav-link">Accueil</a>
            <a href="/menu" class="nav-link">Nos Menus</a>
            <a href="/contact" class="nav-link">Contact</a>
        </nav>
        <div class="header-actions">
            <!-- Panier -->
            <a href="/cart" class="cart-icon position-relative" style="color: var(--vert-primaire); font-size: 22px; margin-right: 10px;" aria-label="Panier<?php echo $cartCount > 0 ? ', ' . $cartCount . ' article' . ($cartCount > 1 ? 's' : '') : ', vide'; ?>">
                <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                <span class="sr-only">Panier<?php echo $cartCount > 0 ? ', ' . $cartCount . ' article' . ($cartCount > 1 ? 's' : '') : ', vide'; ?></span>
                <?php if ($cartCount > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill" style="background-color: #FF8F00; font-size: 10px;" aria-hidden="true">
                        <?php echo $cartCount > 9 ? '9+' : $cartCount; ?>
                    </span>
                <?php endif; ?>
            </a>

            <?php if (Auth::check()): ?>
                <?php if (Auth::isEmployee()): ?>
                    <a href="/admin" class="btn-outline">Administration</a>
                <?php endif; ?>
                <div class="dropdown">
                    <button class="btn-primary-header dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo htmlspecialchars($currentUser['first_name'], ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/order/history"><i class="fas fa-history me-2"></i>Mes commandes</a></li>
                        <li><a class="dropdown-item" href="/user/profile"><i class="fas fa-user me-2"></i>Mon profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/user/logout"><i class="fas fa-sign-out-alt me-2"></i>Deconnexion</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="/user/login" class="btn-outline">Connexion</a>
                <a href="/user/register" class="btn-primary-header">S'inscrire</a>
            <?php endif; ?>

            <!-- Mobile menu button -->
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mobileNav">
                <i class="fas fa-bars" id="menuIcon" aria-hidden="true"></i>
            </button>
        </div>
    </header>

    <!-- Mobile Navigation -->
    <nav class="mobile-nav" id="mobileNav" role="navigation" aria-label="Menu mobile" aria-hidden="true">
        <a href="/"><i class="fas fa-home me-2"></i>Accueil</a>
        <a href="/menu"><i class="fas fa-utensils me-2"></i>Nos Menus</a>
        <a href="/cart"><i class="fas fa-shopping-cart me-2"></i>Panier <?php if ($cartCount > 0): ?>(<?php echo $cartCount; ?>)<?php endif; ?></a>
        <a href="/contact"><i class="fas fa-envelope me-2"></i>Contact</a>
        <div class="mobile-nav-divider"></div>
        <?php if (Auth::check()): ?>
            <?php if (Auth::isEmployee()): ?>
                <a href="/admin"><i class="fas fa-cog me-2"></i>Administration</a>
            <?php endif; ?>
            <a href="/order/history"><i class="fas fa-history me-2"></i>Mes commandes</a>
            <a href="/user/profile"><i class="fas fa-user me-2"></i>Mon profil</a>
            <a href="/user/logout" style="color: #dc3545;"><i class="fas fa-sign-out-alt me-2"></i>Deconnexion</a>
        <?php else: ?>
            <a href="/user/login"><i class="fas fa-sign-in-alt me-2"></i>Connexion</a>
            <a href="/user/register"><i class="fas fa-user-plus me-2"></i>S'inscrire</a>
        <?php endif; ?>
    </nav>

    <script>
        function toggleMobileMenu() {
            const nav = document.getElementById('mobileNav');
            const icon = document.getElementById('menuIcon');
            const btn = document.querySelector('.mobile-menu-btn');
            const isOpen = nav.classList.toggle('show');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
            // Accessibilite
            nav.setAttribute('aria-hidden', !isOpen);
            btn.setAttribute('aria-expanded', isOpen);
            btn.setAttribute('aria-label', isOpen ? 'Fermer le menu' : 'Ouvrir le menu');
        }
    </script>

    <!-- Messages Flash -->
    <?php if (!empty($flashMessages)): ?>
        <div class="flash-container">
            <?php foreach ($flashMessages as $type => $message): ?>
                <div class="alert alert-<?php echo $type === 'error' ? 'danger' : htmlspecialchars($type); ?> alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <main id="main-content" role="main">
