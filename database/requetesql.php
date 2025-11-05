<?php

function get_username_by_id($user_id) {
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT login FROM utilisateurs WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user ? $user['login'] : null;
}

function get_username_by_username($username) {
    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT login FROM utilisateurs WHERE login = :login");
    $stmt->execute(['login' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user ? $user['login'] : null;
}