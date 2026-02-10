<?php
// Inclure la classe Auth pour la gestion de session
require_once __DIR__ . '/../../Core/Auth.php';
Auth::init();
$currentUser = Auth::user();
$flashMessages = Auth::getAllFlash();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) . ' - ' : ''; ?>Vite & Gourmand</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome pour les icones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS personnalise -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #2E7D32;">
        <div class="container">
            <a class="navbar-brand" href="/" style="font-family: 'Playfair Display', serif; font-weight: 700;">
                <i class="fas fa-utensils"></i> Vite & Gourmand
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="fas fa-home"></i> Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/menu">
                            <i class="fas fa-book-open"></i> Nos Menus
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">
                            <i class="fas fa-envelope"></i> Contact
                        </a>
                    </li>

                    <?php if (Auth::check()): ?>
                        <!-- Utilisateur connecte -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($currentUser['first_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="/order/history"><i class="fas fa-history me-2"></i> Mes commandes</a></li>
                                <li><a class="dropdown-item" href="/user/profile"><i class="fas fa-user me-2"></i> Mon profil</a></li>
                                <?php if (Auth::isEmployee()): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/admin"><i class="fas fa-tachometer-alt me-2"></i> Administration</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="/user/logout"><i class="fas fa-sign-out-alt me-2"></i> Deconnexion</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Utilisateur non connecte -->
                        <li class="nav-item">
                            <a class="nav-link" href="/user/login">
                                <i class="fas fa-sign-in-alt"></i> Connexion
                            </a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-warning btn-sm text-dark" href="/user/register" style="font-weight: 600;">
                                <i class="fas fa-user-plus"></i> S'inscrire
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Messages Flash -->
    <?php if (!empty($flashMessages)): ?>
        <div class="container mt-3">
            <?php foreach ($flashMessages as $type => $message): ?>
                <div class="alert alert-<?php echo $type === 'error' ? 'danger' : htmlspecialchars($type); ?> alert-dismissible fade show" role="alert">
                    <?php if ($type === 'success'): ?><i class="fas fa-check-circle me-2"></i><?php endif; ?>
                    <?php if ($type === 'error'): ?><i class="fas fa-exclamation-circle me-2"></i><?php endif; ?>
                    <?php if ($type === 'warning'): ?><i class="fas fa-exclamation-triangle me-2"></i><?php endif; ?>
                    <?php if ($type === 'info'): ?><i class="fas fa-info-circle me-2"></i><?php endif; ?>
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Contenu principal -->
    <main>