<?php
require_once __DIR__ . '/../../Core/Auth.php';
require_once __DIR__ . '/../../Core/Csrf.php';
?>

<div class="container py-5">
    <div class="row">
        <!-- Formulaire de contact -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header" style="background-color: #2E7D32; color: white;">
                    <h4 class="mb-0" style="font-family: 'Playfair Display', serif;">
                        <i class="fas fa-envelope me-2"></i>Nous contacter
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Une question sur nos menus ? Une demande speciale ? N'hesitez pas a nous contacter,
                        nous vous repondrons dans les plus brefs delais.
                    </p>

                    <form action="/contact/send" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">

                        <!-- Honeypot anti-spam -->
                        <div style="display: none;">
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Votre nom *</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                       value="<?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Votre email *</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                            </div>
                            <div class="col-12">
                                <label for="subject" class="form-label">Sujet *</label>
                                <select class="form-select" id="subject" name="subject" required>
                                    <option value="">Choisissez un sujet...</option>
                                    <option value="Information sur un menu">Information sur un menu</option>
                                    <option value="Demande de devis">Demande de devis</option>
                                    <option value="Question sur une commande">Question sur une commande</option>
                                    <option value="Allergies et regimes speciaux">Allergies et regimes speciaux</option>
                                    <option value="Partenariat">Partenariat</option>
                                    <option value="Autre">Autre</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">Votre message *</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required
                                          placeholder="Decrivez votre demande..."></textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="consent" required>
                                    <label class="form-check-label" for="consent">
                                        J'accepte que mes donnees soient utilisees pour traiter ma demande.
                                        <a href="/contact/confidentialite" target="_blank">En savoir plus</a>
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-lg" style="background-color: #FF8F00; color: white;">
                                    <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Informations de contact -->
        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header" style="background-color: #FF8F00; color: white;">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Nos coordonnees
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="rounded-circle p-2 me-3 flex-shrink-0" style="background-color: #E8F5E9;">
                                <i class="fas fa-map-marker-alt" style="color: #2E7D32;"></i>
                            </div>
                            <div>
                                <strong>Adresse</strong>
                                <p class="mb-0 text-muted">
                                    Vite & Gourmand<br>
                                    123 Rue de la Gastronomie<br>
                                    33000 Bordeaux
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="rounded-circle p-2 me-3 flex-shrink-0" style="background-color: #E8F5E9;">
                                <i class="fas fa-phone" style="color: #2E7D32;"></i>
                            </div>
                            <div>
                                <strong>Telephone</strong>
                                <p class="mb-0">
                                    <a href="tel:0556789012" class="text-decoration-none">05 56 78 90 12</a>
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="rounded-circle p-2 me-3 flex-shrink-0" style="background-color: #E8F5E9;">
                                <i class="fas fa-envelope" style="color: #2E7D32;"></i>
                            </div>
                            <div>
                                <strong>Email</strong>
                                <p class="mb-0">
                                    <a href="mailto:contact@viteetgourmand.fr" class="text-decoration-none">contact@viteetgourmand.fr</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Horaires
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr>
                                <td>Lundi - Vendredi</td>
                                <td class="text-end"><strong>9h00 - 18h00</strong></td>
                            </tr>
                            <tr>
                                <td>Samedi</td>
                                <td class="text-end"><strong>9h00 - 12h00</strong></td>
                            </tr>
                            <tr>
                                <td>Dimanche</td>
                                <td class="text-end text-muted">Ferme</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="alert alert-info mt-3 mb-0" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Livraisons possibles 7j/7 sur reservation.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
