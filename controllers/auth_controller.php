<?php
// Contrôleur Auth compatible avec le routeur (fonctions auth_*)

/**
 * Route: /auth/inscription
 */

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

/**
 * Route: /auth/connexion
 */

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

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            set_flash('success', 'Connexion réussie !');
        } else {
            set_flash('error', 'Nom d\'utilisateur ou mot de passe incorrect.');
        }
    }

    load_view_with_layout('auth/connexion', $data);
}

