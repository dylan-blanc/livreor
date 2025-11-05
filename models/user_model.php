<?php
require_once CORE_PATH . '/database.php';

/**
 * Retourne la ligne utilisateur par ID
 */
function get_user_by_id($user_id)
{
    return db_select_one('SELECT * FROM utilisateurs WHERE id = :id', ['id' => $user_id]) ?: null;
}

/**
 * Retourne la ligne utilisateur par username (colonne `login` en base)
 */
function get_user_by_username($username)
{
    return db_select_one('SELECT * FROM utilisateurs WHERE login = :login', ['login' => $username]) ?: null;
}

/**
 * Retourne l'username à partir d'un ID
 */
function get_username_by_id($user_id)
{
    $user = get_user_by_id($user_id);
    return $user['login'] ?? null;
}

/**
 * Retourne l'username à partir d'un username
 */
function get_username_by_username($username)
{
    $user = get_user_by_username($username);
    return $user['login'] ?? null;
}

/**
 * Indique si un username existe (bool)
 */
function username_exists($username)
{
    return get_user_by_username($username) !== null;
}

/**
 * Indique si un utilisateur existe par ID (bool)
 */
function username_exists_by_id($user_id)
{
    return get_user_by_id($user_id) !== null;
}

/**
 * Alias sémantique utilisé parfois dans le code
 */
function user_exists($username)
{
    return username_exists($username);
}

/**
 * Crée un utilisateur
 */
function create_user($username, $password)
{
    // Toujours hasher le mot de passe côté serveur
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    db_execute('INSERT INTO utilisateurs (login, password) VALUES (:login, :password)', [
        'login' => $username,
        'password' => $password_hash,
    ]);
    return (int) db_last_insert_id();
}

// function auth_register($username, $password)
// {
//     if (user_exists($username)) {
//         return false;
//     } else {
//         create_user($username, password_hash($password, PASSWORD_BCRYPT));
//         return true;
//     }
// }
