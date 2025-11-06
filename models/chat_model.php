<?php
require_once CORE_PATH . '/database.php';

function get_chat_messages_by_id($user_id)
{
    return db_select_one('SELECT * FROM utilisateurs WHERE id = :id', ['id' => $user_id]) ?: null;
}


function get_all_chat_messages()
{
        return db_select('
        SELECT c.*, u.login, DATE_FORMAT(c.date_commentaire, "%d/%m/%Y") AS date_commentaire_formatee 
        FROM commentaires c
        INNER JOIN utilisateurs u ON u.id = c.id_utilisateur
        ORDER BY c.date_commentaire ASC');
}

/*
* --- récupère tous les commentaires de la BDD, avec le login de l'utilisateur et la date reformater au format que les ricans sont pas foutu d'utiliser ---
*
* SELECT c.*, u.login, DATE_FORMAT(c.date_commentaire, "%d/%m/%Y") AS date_commentaire_formatee
* selectionner toutes les colonnes de la table commentaires (c.*) et la colonne login de la table utilisateurs (u.login) formater la date au format jour/mois/année
* 
* FROM commentaires c
* INNER JOIN utilisateurs u ON u.id = c.id_utilisateur
* faire une jointure entre la table commentaires (c) et la table utilisateurs (u) en utilisant la colonne id de la table utilisateurs et la colonne id_utilisateur de la table commentaires
*/



function save_chat_message($id_utilisateur, $message)
{
    return db_execute(
        'INSERT INTO commentaires (id_utilisateur, commentaire) VALUES (:id_utilisateur, :commentaire)',
        [
            'id_utilisateur' => $id_utilisateur,
            'commentaire' => $message
        ]
    );
}
