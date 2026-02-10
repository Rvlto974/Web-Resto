<?php
require_once __DIR__ . '/../Views/core/Controller.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Core/Csrf.php';

/**
 * ContactController - Page de contact et pages legales
 */
class ContactController extends Controller {

    /**
     * Page de contact
     */
    public function index() {
        $user = Auth::check() ? Auth::user() : null;

        $this->view('contact/index', [
            'title' => 'Contact',
            'user' => $user,
            'csrfToken' => Csrf::getToken()
        ]);
    }

    /**
     * Envoi du formulaire de contact
     */
    public function send() {
        if (!$this->isPost()) {
            $this->redirect('/contact');
        }

        if (!Csrf::validateRequest()) {
            Auth::setFlash('error', 'Token de securite invalide.');
            $this->redirect('/contact');
        }

        // Protection anti-spam (honeypot)
        if (!empty($_POST['website'])) {
            // C'est probablement un bot
            Auth::setFlash('success', 'Votre message a ete envoye.');
            $this->redirect('/contact');
        }

        // Validation
        $errors = [];

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name)) {
            $errors[] = 'Le nom est obligatoire.';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'L\'email est invalide.';
        }

        if (empty($subject)) {
            $errors[] = 'Le sujet est obligatoire.';
        }

        if (empty($message)) {
            $errors[] = 'Le message est obligatoire.';
        }

        if (strlen($message) < 10) {
            $errors[] = 'Le message doit contenir au moins 10 caracteres.';
        }

        if (!empty($errors)) {
            Auth::setFlash('error', implode('<br>', $errors));
            $this->redirect('/contact');
        }

        // TODO: Envoyer l'email

        // Pour le moment, on simule l'envoi
        Auth::setFlash('success', 'Votre message a bien ete envoye. Nous vous repondrons dans les plus brefs delais.');
        $this->redirect('/contact');
    }

    /**
     * Mentions legales
     */
    public function mentionsLegales() {
        $this->view('pages/mentions-legales', [
            'title' => 'Mentions legales'
        ]);
    }

    /**
     * Conditions generales de vente
     */
    public function cgv() {
        $this->view('pages/cgv', [
            'title' => 'Conditions Generales de Vente'
        ]);
    }

    /**
     * Politique de confidentialite
     */
    public function confidentialite() {
        $this->view('pages/politique-confidentialite', [
            'title' => 'Politique de Confidentialite'
        ]);
    }
}
