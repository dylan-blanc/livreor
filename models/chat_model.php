<?php

function get_chat_messages_by_id($user_id)
{
    return db_select_one('SELECT * FROM utilisateurs WHERE id = :id', ['id' => $user_id]) ?: null;
}



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
