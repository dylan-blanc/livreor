<?php
// Contrôleur pour la page d'accueil

/**
 * Page d'accueil
 */
function home_index()
{
    $data = [
        'title' => 'Accueil',
        'message' => 'Bienvenue sur votre application PHP MVC !',
        'features' => [
            'Architecture MVC claire',
            'Système de routing simple',
            'Templating HTML/CSS',
            'Gestion de base de données',
            'Sécurité intégrée'
        ]
    ];

    load_view_with_layout('home/index', $data);
}


/**
 * Page du chat
 */

function home_chat()
{
    $data = [
        'title' => 'Chat',
        'message' => 'Bienvenue sur le chat !',

    ];

    if (is_post()) {
        if (!is_logged_in()) {
            set_flash('error', 'Vous devez être connecté pour envoyer des messages.');
            redirect('auth/connexion');
        }

        $message = clean_input(post('message'));
        $user_id = $_SESSION['user_id'];

        if (!empty($message)) {
            if (save_chat_message($user_id, $message)) {
                set_flash('success', 'Message envoyé avec succès !');
            } else {
                set_flash('error', 'Erreur lors de l\'envoi du message. Veuillez réessayer.');
            }
        } else {
            set_flash('error', 'Le message ne peut pas être vide.');
        }

        redirect('home/chat');
    }

    load_view_with_layout('home/chat', $data);
}
