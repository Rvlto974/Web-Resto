<?php
require_once __DIR__ . '/../Views/core/Controller.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Core/Csrf.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Services/EmailService.php';

/**
 * UserController - Gestion de l'authentification et du profil
 */
class UserController extends Controller {

    /**
     * Affiche le formulaire d'inscription ou traite l'inscription
     */
    public function register() {
        // Si deja connecte, rediriger vers l'accueil
        if (Auth::check()) {
            $this->redirect('/');
        }

        $errors = [];
        $old = [];

        if ($this->isPost()) {
            // Verifier le token CSRF
            if (!Csrf::validateRequest()) {
                $errors[] = 'Session expiree. Veuillez reessayer.';
            } else {
                // Recuperer et nettoyer les donnees
                $old = [
                    'first_name' => trim($_POST['first_name'] ?? ''),
                    'last_name' => trim($_POST['last_name'] ?? ''),
                    'email' => trim($_POST['email'] ?? ''),
                    'phone' => trim($_POST['phone'] ?? ''),
                    'address' => trim($_POST['address'] ?? ''),
                    'city' => trim($_POST['city'] ?? ''),
                    'postal_code' => trim($_POST['postal_code'] ?? '')
                ];
                $password = $_POST['password'] ?? '';
                $passwordConfirm = $_POST['password_confirm'] ?? '';

                // Validation des champs obligatoires
                if (empty($old['first_name'])) {
                    $errors[] = 'Le prenom est obligatoire.';
                }
                if (empty($old['last_name'])) {
                    $errors[] = 'Le nom est obligatoire.';
                }
                if (empty($old['email'])) {
                    $errors[] = 'L\'email est obligatoire.';
                } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'L\'email n\'est pas valide.';
                }
                if (empty($old['phone'])) {
                    $errors[] = 'Le telephone est obligatoire.';
                }

                // Validation du mot de passe
                $passwordValidation = User::validatePassword($password);
                if (!$passwordValidation['valid']) {
                    $errors = array_merge($errors, $passwordValidation['errors']);
                }

                // Confirmation du mot de passe
                if ($password !== $passwordConfirm) {
                    $errors[] = 'Les mots de passe ne correspondent pas.';
                }

                // Verifier si l'email existe deja
                if (empty($errors) && User::findByEmail($old['email'])) {
                    $errors[] = 'Cet email est deja utilise.';
                }

                // Si pas d'erreurs, creer le compte
                if (empty($errors)) {
                    $verificationToken = User::generateVerificationToken();

                    $userId = User::create([
                        'first_name' => $old['first_name'],
                        'last_name' => $old['last_name'],
                        'email' => $old['email'],
                        'phone' => $old['phone'],
                        'password' => $password,
                        'address' => $old['address'],
                        'city' => $old['city'],
                        'postal_code' => $old['postal_code'],
                        'verification_token' => $verificationToken,
                        'role' => 'client'
                    ]);

                    if ($userId) {
                        // Envoyer email de bienvenue
                        $newUser = User::findById($userId);
                        if ($newUser) {
                            EmailService::sendWelcome($newUser);
                        }
                        Auth::setFlash('success', 'Votre compte a ete cree avec succes ! Vous pouvez maintenant vous connecter.');
                        $this->redirect('/user/login');
                    } else {
                        $errors[] = 'Une erreur est survenue lors de la creation du compte.';
                    }
                }
            }
        }

        $this->view('user/register', [
            'title' => 'Inscription',
            'errors' => $errors,
            'old' => $old,
            'csrf' => Csrf::getInputField()
        ]);
    }

    /**
     * Affiche le formulaire de connexion ou traite la connexion
     */
    public function login() {
        // Si deja connecte, rediriger vers l'accueil
        if (Auth::check()) {
            $this->redirect('/');
        }

        $errors = [];
        $email = '';

        if ($this->isPost()) {
            // Verifier le token CSRF
            if (!Csrf::validateRequest()) {
                $errors[] = 'Session expiree. Veuillez reessayer.';
            } else {
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';

                if (empty($email) || empty($password)) {
                    $errors[] = 'Veuillez remplir tous les champs.';
                } else {
                    // Chercher l'utilisateur
                    $user = User::findByEmail($email);

                    if (!$user) {
                        $errors[] = 'Identifiants incorrects.';
                    } elseif (!User::verifyPassword($password, $user['password_hash'])) {
                        $errors[] = 'Identifiants incorrects.';
                    } elseif (!$user['is_active']) {
                        $errors[] = 'Votre compte a ete desactive. Contactez-nous pour plus d\'informations.';
                    } else {
                        // Connexion reussie
                        Auth::login($user);
                        User::updateLastLogin($user['id']);

                        Auth::setFlash('success', 'Bienvenue, ' . htmlspecialchars($user['first_name']) . ' !');

                        // Rediriger selon le role
                        if (Auth::isAdmin() || Auth::isEmployee()) {
                            $this->redirect('/admin');
                        } else {
                            $this->redirect('/');
                        }
                    }
                }
            }
        }

        $this->view('user/login', [
            'title' => 'Connexion',
            'errors' => $errors,
            'email' => $email,
            'csrf' => Csrf::getInputField()
        ]);
    }

    /**
     * Deconnexion
     */
    public function logout() {
        Auth::logout();
        Auth::setFlash('success', 'Vous avez ete deconnecte.');
        $this->redirect('/');
    }

    /**
     * Affiche et modifie le profil utilisateur
     */
    public function profile() {
        Auth::requireAuth();

        $user = Auth::user();
        $errors = [];
        $success = false;

        if ($this->isPost()) {
            if (!Csrf::validateRequest()) {
                $errors[] = 'Session expiree. Veuillez reessayer.';
            } else {
                $data = [
                    'first_name' => trim($_POST['first_name'] ?? ''),
                    'last_name' => trim($_POST['last_name'] ?? ''),
                    'phone' => trim($_POST['phone'] ?? ''),
                    'address' => trim($_POST['address'] ?? ''),
                    'city' => trim($_POST['city'] ?? ''),
                    'postal_code' => trim($_POST['postal_code'] ?? '')
                ];

                // Validation
                if (empty($data['first_name'])) {
                    $errors[] = 'Le prenom est obligatoire.';
                }
                if (empty($data['last_name'])) {
                    $errors[] = 'Le nom est obligatoire.';
                }
                if (empty($data['phone'])) {
                    $errors[] = 'Le telephone est obligatoire.';
                }

                if (empty($errors)) {
                    if (User::update($user['id'], $data)) {
                        Auth::refresh();
                        $user = Auth::user();
                        Auth::setFlash('success', 'Profil mis a jour avec succes.');
                        $success = true;
                    } else {
                        $errors[] = 'Erreur lors de la mise a jour.';
                    }
                }
            }
        }

        $this->view('user/profile', [
            'title' => 'Mon profil',
            'user' => $user,
            'errors' => $errors,
            'csrf' => Csrf::getInputField()
        ]);
    }

    /**
     * Changement de mot de passe
     */
    public function password() {
        Auth::requireAuth();

        $errors = [];

        if ($this->isPost()) {
            if (!Csrf::validateRequest()) {
                $errors[] = 'Session expiree. Veuillez reessayer.';
            } else {
                $currentPassword = $_POST['current_password'] ?? '';
                $newPassword = $_POST['new_password'] ?? '';
                $confirmPassword = $_POST['confirm_password'] ?? '';

                $user = Auth::user();

                // Verifier le mot de passe actuel
                if (!User::verifyPassword($currentPassword, $user['password_hash'])) {
                    $errors[] = 'Le mot de passe actuel est incorrect.';
                }

                // Valider le nouveau mot de passe
                $validation = User::validatePassword($newPassword);
                if (!$validation['valid']) {
                    $errors = array_merge($errors, $validation['errors']);
                }

                // Verifier la confirmation
                if ($newPassword !== $confirmPassword) {
                    $errors[] = 'Les nouveaux mots de passe ne correspondent pas.';
                }

                if (empty($errors)) {
                    if (User::updatePassword($user['id'], $newPassword)) {
                        Auth::setFlash('success', 'Mot de passe modifie avec succes.');
                        $this->redirect('/user/profile');
                    } else {
                        $errors[] = 'Erreur lors de la modification.';
                    }
                }
            }
        }

        $this->view('user/password', [
            'title' => 'Changer le mot de passe',
            'errors' => $errors,
            'csrf' => Csrf::getInputField()
        ]);
    }

    /**
     * Demande de reinitialisation de mot de passe
     */
    public function forgotPassword() {
        if (Auth::check()) {
            $this->redirect('/');
        }

        $errors = [];
        $success = false;

        if ($this->isPost()) {
            if (!Csrf::validateRequest()) {
                $errors[] = 'Session expiree. Veuillez reessayer.';
            } else {
                $email = trim($_POST['email'] ?? '');

                if (empty($email)) {
                    $errors[] = 'Veuillez entrer votre email.';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Email invalide.';
                } else {
                    $user = User::findByEmail($email);

                    // On affiche toujours un message de succes pour ne pas reveler si l'email existe
                    if ($user) {
                        $token = User::createPasswordResetToken($user['id']);
                        // TODO: Envoyer l'email avec le lien de reinitialisation
                        // EmailService::sendPasswordReset($user, $token);
                    }

                    $success = true;
                }
            }
        }

        $this->view('user/forgot-password', [
            'title' => 'Mot de passe oublie',
            'errors' => $errors,
            'success' => $success,
            'csrf' => Csrf::getInputField()
        ]);
    }

    /**
     * Reinitialisation du mot de passe avec token
     */
    public function resetPassword($token = null) {
        if (Auth::check()) {
            $this->redirect('/');
        }

        if (!$token) {
            Auth::setFlash('error', 'Lien de reinitialisation invalide.');
            $this->redirect('/user/login');
        }

        // Verifier le token
        $reset = User::verifyPasswordResetToken($token);
        if (!$reset) {
            Auth::setFlash('error', 'Ce lien de reinitialisation est invalide ou a expire.');
            $this->redirect('/user/login');
        }

        $errors = [];

        if ($this->isPost()) {
            if (!Csrf::validateRequest()) {
                $errors[] = 'Session expiree. Veuillez reessayer.';
            } else {
                $password = $_POST['password'] ?? '';
                $confirmPassword = $_POST['password_confirm'] ?? '';

                // Valider le mot de passe
                $validation = User::validatePassword($password);
                if (!$validation['valid']) {
                    $errors = array_merge($errors, $validation['errors']);
                }

                if ($password !== $confirmPassword) {
                    $errors[] = 'Les mots de passe ne correspondent pas.';
                }

                if (empty($errors)) {
                    // Mettre a jour le mot de passe
                    if (User::updatePassword($reset['user_id'], $password)) {
                        User::markResetTokenUsed($token);
                        Auth::setFlash('success', 'Votre mot de passe a ete reinitialise. Vous pouvez maintenant vous connecter.');
                        $this->redirect('/user/login');
                    } else {
                        $errors[] = 'Erreur lors de la reinitialisation.';
                    }
                }
            }
        }

        $this->view('user/reset-password', [
            'title' => 'Reinitialiser le mot de passe',
            'token' => $token,
            'errors' => $errors,
            'csrf' => Csrf::getInputField()
        ]);
    }
}
