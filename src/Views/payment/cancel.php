<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 100px; height: 100px; background-color: #f8d7da;">
                            <i class="fas fa-times fa-3x text-danger"></i>
                        </div>
                    </div>

                    <h1 class="h3 mb-3" style="font-family: 'Playfair Display', serif; color: #dc3545;">
                        Paiement annule
                    </h1>

                    <p class="text-muted mb-4">
                        Votre paiement a ete annule ou a echoue.<br>
                        Votre commande n'a pas ete validee.
                    </p>

                    <div class="alert alert-info text-start">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Que faire ?</strong>
                        <ul class="mb-0 mt-2">
                            <li>Vous pouvez reessayer le paiement</li>
                            <li>Ou choisir le paiement a la livraison</li>
                            <li>Votre panier a ete conserve</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2">
                        <?php if (isset($orderId) && $orderId): ?>
                            <a href="/payment/createSession/<?php echo $orderId; ?>" class="btn btn-lg" style="background-color: #FF8F00; color: white;">
                                <i class="fas fa-redo me-2"></i>Reessayer le paiement
                            </a>
                        <?php endif; ?>
                        <a href="/cart" class="btn btn-lg btn-outline-secondary">
                            <i class="fas fa-shopping-cart me-2"></i>Retour au panier
                        </a>
                        <a href="/menu" class="btn btn-link text-muted">
                            Continuer mes achats
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
