    </main>

    <!-- Footer -->
    <footer class="footer-home" style="text-align: center;">
        <div class="footer-grid" style="text-align: center;">
            <div class="footer-col" style="text-align: center;">
                <h4>Vite & Gourmand</h4>
                <p>Traiteur d'exception a Bordeaux depuis 25 ans. Nous mettons notre passion au service de vos evenements.</p>
                <div class="footer-social">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="footer-col" style="text-align: center;">
                <h4>Horaires</h4>
                <p>Lundi - Vendredi : 9h - 18h</p>
                <p>Samedi : 9h - 12h</p>
                <p>Dimanche : Ferme</p>
            </div>

            <div class="footer-col" style="text-align: center;">
                <h4>Contact</h4>
                <p>📞 05 56 12 34 56</p>
                <p>✉️ contact@viteetgourmand.fr</p>
                <p>📍 Bordeaux, France</p>
            </div>
        </div>

        <div class="footer-bottom">
            <a href="/mentions-legales">Mentions legales</a> |
            <a href="/cgv">CGV</a> |
            <a href="/confidentialite">Politique de confidentialite</a>
            <p>&copy; <?php echo date('Y'); ?> Vite & Gourmand - Tous droits reserves</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
    <script>
    // Dropdown utilisateur (backup inline)
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('userDropdownBtn');
        var menu = document.getElementById('userDropdownMenu');
        if (btn && menu) {
            btn.onclick = function(e) {
                e.stopPropagation();
                menu.classList.toggle('show');
            };
            document.onclick = function() {
                menu.classList.remove('show');
            };
        }
    });
    </script>
</body>
</html>
