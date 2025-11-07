<?php

function auth_inscription()
{
    // Préparation des données pour la vue
    $data = [
        'title' => 'Inscription'
    ];

    // === TRAITEMENT DU FORMULAIRE D'INSCRIPTION ===

    if (is_post()) {
        // Récupération et nettoyage des données du formulaire
        $username = clean_input(post('username'));           // username (nettoyé)
        $password = post('password');                    // Mot de passe (non nettoyé)
        $confirm_password = post('confirm_password');    // Confirmation mot de passe

        // === VALIDATION COMPLÈTE SELON LE CAHIER DES CHARGES ===

        // Vérification des champs obligatoires
        if (empty($username) || empty($password) || empty($confirm_password)) {
            set_flash('error', 'Tous les champs sont obligatoires.');

            // Validation du format du nom d'utilisateur (2-50 caractères, lettres uniquement)
        } elseif (!validate_name($username)) {
            set_flash('error', 'Le nom d\'utilisateur doit contenir entre 2 et 50 caractères, lettres, espaces et tirets uniquement.');

            // Validation de la complexité du mot de passe
        } elseif (!validate_password($password)) {
            set_flash('error', 'Le mot de passe doit contenir au moins 5 caractères avec majuscule, minuscule et chiffre.');

            // Vérification de la correspondance des mots de passe
        } elseif ($password !== $confirm_password) {
            set_flash('error', 'Les mots de passe ne correspondent pas.');

            // Vérification de l'unicité de l'email
        } elseif (get_user_by_username($username)) {
            set_flash('error', 'Le nom d\'utilisateur est déjà utilisé par un autre compte.');
        } else {
            // === TRAITEMENT RÉUSSI - CRÉATION DU COMPTE ===

            // Formatage des noms (première lettre majuscule, reste minuscule)
            $username = format_username($username);

            // === CRÉATION DE L'UTILISATEUR EN BASE ===
            $user_id = create_user($username, $password);

            if ($user_id) {
                set_flash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
                redirect('auth/connexion');
            } else {
                set_flash('error', 'Erreur lors de l\'inscription.');
            }
        }
    }

    // Harmoniser le chemin de vue (minuscules)
    load_view_with_layout('auth/inscription', $data);
}


function auth_connexion()
{
    $data = [
        'title' => 'Connexion',
    ];

    if (is_post()) {
        $username = clean_input(post('username'));
        $password = post('password');

        $user = get_user_by_username($username);
        if ($user && password_verify($password, $user['password'])) {
            // Sécuriser la session à la connexion
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_regenerate_id(true);
            }

            // Stocker les infos minimales en session
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['username'] = $user['login'] ?? $username;
            $_SESSION['last_activity'] = time();

            set_flash('success', 'Connexion réussie !');
            // Rediriger vers la page d'accueil (ou tableau de bord)
            redirect('');
        } else {
            set_flash('error', 'Nom d\'utilisateur ou mot de passe incorrect.');
        }
    }

    load_view_with_layout('auth/connexion', $data);
}

function auth_profil()
{
    // Préparation des données pour la vue
    $data = [
        'title' => 'profil'
    ];

    if (isset($_SESSION['user_id'])) {
        $data['messages'] = get_message_by_user_id($_SESSION['user_id']);
    } else {
        $data['messages'] = [];
    }


    if (is_post()) {
        // Récupération et nettoyage des données du formulaire
        $username = clean_input(post('username'));
        $password = post('password');
        $confirm_password = post('confirm_password');

        // Vérification des champs obligatoires
        if (empty($username) || empty($password) || empty($confirm_password)) {
            set_flash('error', 'Tous les champs sont obligatoires.');

            // Validation du format du nom d'utilisateur (2-50 caractères, lettres uniquement)
        } elseif (!validate_name($username)) {
            set_flash('error', 'Le nom d\'utilisateur doit contenir entre 2 et 50 caractères, lettres, espaces et tirets uniquement.');

            // Validation du mot de passe
        } elseif (!validate_password($password)) {
            set_flash('error', 'Le mot de passe doit contenir au moins 5 caractères avec majuscule, minuscule et chiffre.');

            // Vérification de la correspondance des mots de passe
        } elseif ($password !== $confirm_password) {
            set_flash('error', 'Les mots de passe ne correspondent pas.');

            // Vérification de l'unicité de l'utilisateur
        } elseif (($u = get_user_by_username($username)) && (int)$u['id'] !== (int)$_SESSION['user_id']) {
            set_flash('error', 'Le nom d\'utilisateur est déjà utilisé par un autre compte.');
        } else {
            // === TRAITEMENT RÉUSSI - MISE À JOUR DU PROFIL ===

            // Formatage des noms (première lettre majuscule, reste minuscule)
            $username = format_username($username);

            // === MISE À JOUR DE L'UTILISATEUR EN BASE ===
            // Le modèle `update_user` attend (username, password) et gère le hash en interne
            $updated = update_user($username, $password);

            if ($updated) {

                if (session_status() === PHP_SESSION_ACTIVE) {
                    session_regenerate_id(true);
                }

                $_SESSION['username'] = $username;
                set_flash('success', 'Profil mis à jour avec succès !');
                redirect('auth/profil');
            } else {
                set_flash('error', 'Erreur lors de la mise à jour du profil.');
            }
        }
    }
    load_view_with_layout('auth/profil', $data);
}



/**
 * Route: /auth/deconnexion
 */
function auth_deconnexion()
{
    sess_destroy();
}
