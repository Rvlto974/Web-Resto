<style>
    .hero {
        height: 600px;
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
                    url('https://images.unsplash.com/photo-1555244162-803834f70033?w=1600') center/cover;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: var(--blanc);
    }
    .hero-title {
        font-size: 72px;
        margin-bottom: 20px;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        color: #fff;
    }
    .hero-subtitle {
        font-size: 26px;
        margin-bottom: 40px;
        font-weight: 300;
        opacity: 0.95;
    }
    .hero .btn-primary {
        background: var(--orange);
        padding: 18px 45px;
        font-size: 18px;
        box-shadow: 0 6px 25px rgba(255,143,0,0.4);
        color: #fff;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: background 0.3s;
    }
    .hero .btn-primary:hover { background: var(--orange-hover); color: #fff; }
    .section { padding: 80px 60px; }
    .section-gray { background: var(--fond-gris); }
    .section-title {
        font-size: 42px;
        text-align: center;
        margin-bottom: 60px;
        color: var(--vert-primaire);
    }
    .cards-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
    }
    .card {
        background: var(--blanc);
        border-radius: 20px;
        padding: 45px 35px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        border: none;
    }
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.12);
    }
    .card-icon {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--vert-primaire), var(--vert-fonce));
        margin: 0 auto 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--blanc);
        font-size: 36px;
    }
    .card-title {
        font-size: 24px;
        color: var(--vert-primaire);
        margin-bottom: 15px;
    }
    .card-text {
        font-size: 16px;
        color: var(--texte-light);
        line-height: 1.7;
    }
    .avis-container {
        max-width: 900px;
        margin: 0 auto;
        position: relative;
    }
    .avis-slider {
        position: relative;
        overflow: hidden;
    }
    .avis-card {
        background: var(--blanc);
        border-radius: 20px;
        padding: 50px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        display: none;
        align-items: center;
        gap: 40px;
        opacity: 0;
        transition: opacity 0.4s ease-in-out;
    }
    .avis-card.active {
        display: flex;
        opacity: 1;
    }
    .avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: url('https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150') center/cover;
        flex-shrink: 0;
        border: 4px solid var(--vert-primaire);
    }
    .avis-content { flex: 1; }
    .avis-text {
        font-size: 20px;
        font-style: italic;
        color: var(--texte-light);
        margin-bottom: 20px;
        line-height: 1.7;
    }
    .stars { color: var(--orange); font-size: 24px; }
    .avis-author {
        font-weight: 700;
        color: var(--texte);
        font-size: 18px;
        margin-top: 10px;
    }
    .nav-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: var(--blanc);
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: var(--vert-primaire);
        cursor: pointer;
        transition: all 0.3s;
        border: none;
    }
    .nav-arrow:hover {
        background: var(--vert-primaire);
        color: var(--blanc);
    }
    .nav-prev { left: -80px; }
    .nav-next { right: -80px; }
    .pagination {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-top: 35px;
    }
    .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #BDBDBD;
        cursor: pointer;
        transition: background 0.3s, transform 0.2s;
        border: none;
    }
    .dot:hover { transform: scale(1.2); }
    .dot.active { background: var(--vert-primaire); }
    .footer {
        background: var(--vert-fonce);
        padding: 60px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 50px;
        color: var(--blanc);
        text-align: center;
    }
    .footer-col h4 {
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        margin-bottom: 25px;
        color: #fff;
    }
    .footer-col p {
        font-size: 15px;
        margin-bottom: 10px;
        opacity: 0.9;
    }
    .footer-social {
        display: flex;
        gap: 15px;
        margin-top: 20px;
        justify-content: center;
    }
    .social-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        transition: background 0.3s;
        color: #fff;
        text-decoration: none;
    }
    .social-icon:hover { background: var(--orange); color: #fff; }
    .footer-bottom {
        grid-column: 1 / -1;
        text-align: center;
        padding-top: 35px;
        border-top: 1px solid rgba(255,255,255,0.2);
        font-size: 14px;
        opacity: 0.8;
    }
    .footer-bottom a { color: var(--blanc); margin: 0 10px; text-decoration: none; }
    .footer-bottom a:hover { color: var(--orange); }

    @media (max-width: 992px) {
        .cards-grid { grid-template-columns: 1fr; padding: 0 20px; }
        .hero-title { font-size: 42px; }
        .hero-subtitle { font-size: 18px; }
        .section { padding: 50px 20px; }
        .section-title { font-size: 32px; }
        .avis-card { flex-direction: column; text-align: center; }
        .nav-arrow { display: none; }
        .footer { grid-template-columns: 1fr; padding: 40px 20px; }
    }

    @media (max-width: 576px) {
        .hero { height: 400px; }
        .hero-title { font-size: 32px; }
        .hero-subtitle { font-size: 16px; margin-bottom: 25px; }
        .hero .btn-primary { padding: 14px 30px; font-size: 16px; }
        .section { padding: 40px 15px; }
        .section-title { font-size: 26px; margin-bottom: 40px; }
        .card { padding: 30px 20px; }
        .card-icon { width: 70px; height: 70px; font-size: 28px; }
        .card-title { font-size: 20px; }
        .card-text { font-size: 14px; }
        .avis-card { padding: 25px 20px; }
        .avis-text { font-size: 16px; }
        .avatar { width: 80px; height: 80px; }
    }
</style>

<!-- Hero -->
<section class="hero">
    <h1 class="hero-title">Vite & Gourmand</h1>
    <p class="hero-subtitle">Traiteur d'exception depuis 25 ans a Bordeaux</p>
    <a href="/menu" class="btn-primary">Decouvrir nos menus</a>
</section>

<!-- Pourquoi nous choisir -->
<section class="section section-gray">
    <h2 class="section-title">Pourquoi nous choisir</h2>

    <div class="cards-grid">
        <div class="card">
            <div class="card-icon">🏆</div>
            <h3 class="card-title">25 ans d'experience</h3>
            <p class="card-text">Une expertise reconnue dans l'art culinaire et le service traiteur haut de gamme depuis 1999</p>
        </div>

        <div class="card">
            <div class="card-icon">🌿</div>
            <h3 class="card-title">Produits locaux</h3>
            <p class="card-text">Ingredients frais et de saison, issus de producteurs locaux rigoureusement selectionnes</p>
        </div>

        <div class="card">
            <div class="card-icon">🚚</div>
            <h3 class="card-title">Livraison Bordeaux</h3>
            <p class="card-text">Service de livraison professionnel et ponctuel dans Bordeaux et ses environs</p>
        </div>
    </div>
</section>

<!-- Avis clients -->
<section class="section">
    <h2 class="section-title">Avis clients</h2>

    <div class="avis-container">
        <button class="nav-arrow nav-prev" onclick="changeAvis(-1)" aria-label="Avis precedent">‹</button>

        <div class="avis-slider">
            <div class="avis-card active" data-index="0">
                <div class="avatar" style="background-image: url('https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150');"></div>
                <div class="avis-content">
                    <p class="avis-text">"Excellent service, menus delicieux et livraison impeccable ! Nous avons fait appel a Vite & Gourmand pour notre mariage et tous nos invites ont ete enchantes. Je recommande vivement !"</p>
                    <div class="stars">★★★★★</div>
                    <p class="avis-author">Marie D. - Mariage, Decembre 2024</p>
                </div>
            </div>

            <div class="avis-card" data-index="1">
                <div class="avatar" style="background-image: url('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150');"></div>
                <div class="avis-content">
                    <p class="avis-text">"Pour l'anniversaire de ma femme, j'ai commande le menu gastronomique. Presentation soignee, saveurs raffinées, tout etait parfait. Le service client est vraiment a l'ecoute !"</p>
                    <div class="stars">★★★★★</div>
                    <p class="avis-author">Thomas L. - Anniversaire, Janvier 2025</p>
                </div>
            </div>

            <div class="avis-card" data-index="2">
                <div class="avatar" style="background-image: url('https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150');"></div>
                <div class="avis-content">
                    <p class="avis-text">"Traiteur de confiance pour nos evenements d'entreprise depuis 3 ans. Qualite constante, equipe professionnelle et toujours des nouveautes. Nos collaborateurs adorent !"</p>
                    <div class="stars">★★★★★</div>
                    <p class="avis-author">Sophie M. - Seminaire entreprise, Fevrier 2025</p>
                </div>
            </div>

            <div class="avis-card" data-index="3">
                <div class="avatar" style="background-image: url('https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150');"></div>
                <div class="avis-content">
                    <p class="avis-text">"Le buffet de Noel etait exceptionnel ! Foie gras, saumon, desserts... Tout etait frais et delicieux. Mes invites en parlent encore. Merci a toute l'equipe !"</p>
                    <div class="stars">★★★★★</div>
                    <p class="avis-author">Pierre B. - Reveillon, Decembre 2024</p>
                </div>
            </div>

            <div class="avis-card" data-index="4">
                <div class="avatar" style="background-image: url('https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150');"></div>
                <div class="avis-content">
                    <p class="avis-text">"Menu vegetarien parfait pour notre reception. Enfin un traiteur qui propose des options veggie gourmandes et creatives. La presentation etait magnifique !"</p>
                    <div class="stars">★★★★★</div>
                    <p class="avis-author">Claire V. - Reception, Novembre 2024</p>
                </div>
            </div>

            <div class="avis-card" data-index="5">
                <div class="avatar" style="background-image: url('https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150');"></div>
                <div class="avis-content">
                    <p class="avis-text">"Bapteme de notre fille reussi grace a Vite & Gourmand. Service impeccable, equipe souriante et plats savoureux. Le rapport qualite-prix est excellent !"</p>
                    <div class="stars">★★★★★</div>
                    <p class="avis-author">Laurent G. - Bapteme, Octobre 2024</p>
                </div>
            </div>
        </div>

        <button class="nav-arrow nav-next" onclick="changeAvis(1)" aria-label="Avis suivant">›</button>
    </div>

    <div class="pagination">
        <span class="dot active" onclick="goToAvis(0)"></span>
        <span class="dot" onclick="goToAvis(1)"></span>
        <span class="dot" onclick="goToAvis(2)"></span>
        <span class="dot" onclick="goToAvis(3)"></span>
        <span class="dot" onclick="goToAvis(4)"></span>
        <span class="dot" onclick="goToAvis(5)"></span>
    </div>
</section>

<script>
let currentAvis = 0;
const totalAvis = 6;

function changeAvis(direction) {
    currentAvis += direction;
    if (currentAvis < 0) currentAvis = totalAvis - 1;
    if (currentAvis >= totalAvis) currentAvis = 0;
    updateAvis();
}

function goToAvis(index) {
    currentAvis = index;
    updateAvis();
}

function updateAvis() {
    // Update cards
    document.querySelectorAll('.avis-card').forEach((card, index) => {
        card.classList.toggle('active', index === currentAvis);
    });
    // Update dots
    document.querySelectorAll('.dot').forEach((dot, index) => {
        dot.classList.toggle('active', index === currentAvis);
    });
}
</script>
