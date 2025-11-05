<?php
/**
 * Point d'entrée principal de l'application PHP MVC
 * 
 * Ce fichier initialise l'application et lance le système de routing
 */

// Charger le bootstrap qui initialise tout
require_once '../bootstrap.php';

// Lancer le routeur
route();

