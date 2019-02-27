<?php
/*
inclu par :
    [Tous les fichiers]
définition :
    - Connecte à la base de données
    - Créer les tables si elles n'existent pas
*/

    $host = "127.0.0.1";
    $user = "root";
    $pass = "";
    $base = "aqua_communication_system";

    $db = new mysqli($host, $user, $pass);

    $db->query("CREATE DATABASE IF NOT EXISTS $base CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    $db->select_db($base);

    // Création table members :
    $db->query("CREATE TABLE IF NOT EXISTS members (
        id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
        name VARCHAR(255) NOT NULL,
        avatar VARCHAR(255) NOT NULL DEFAULT 'default.png',
        description VARCHAR(1200),
        grade ENUM('User','Collaborater','Admin') NOT NULL DEFAULT 'User',
        status BIGINT NOT NULL DEFAULT '0',
        password TEXT NULL,
        PRIMARY KEY(id)
    )");

    // Création table messages :
    $db->query("CREATE TABLE IF NOT EXISTS messages_admin (
        id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
        id_author BIGINT UNSIGNED NOT NULL,
        text VARCHAR(280) NOT NULL,
        time DATETIME NOT NULL
        PRIMARY KEY(id)
    )");

    // Création table online :
    $db->query("CREATE TABLE IF NOT EXISTS online (
        id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
        ip VARCHAR(250) NOT NULL,
        id_actif BIGINT UNSIGNED NOT NULL,
        time BIGINT UNSIGNED NOT NULL,
        PRIMARY KEY(id)
    )");

    $db->query("CREATE TABLE IF NOT EXISTS checklist (
        id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
        id_author BIGINT UNSIGNED NOT NULL,
        name VARCHAR(255) NOT NULL,
        delegate TEXT NOT NULL,
        status SMALLINT UNSIGNED NOT NULL DEFAULT '0';
        time BIGINT UNSIGNED NULL,
        PRIMARY KEY(id)
    )");
?>