<?php

    $host = "127.0.0.1";
    $user = "root";
    $pass = "";
    $base = "aqua_communication_system";

    $db = new mysqli($host, $user, $pass);

    $db->query("CREATE DATABASE $base CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    $db->select_db($base);

    // Création table members :
    $db->query("CREATE TABLE IF NOT EXISTS members (
        id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
        name VARCHAR(255) NOT NULL,
        avatar VARCHAR(255) NOT NULL DEFAULT 'default.png',
        description VARCHAR(1200),
        grade ENUM('User','Collaborater','Admin') NOT NULL DEFAULT 'User',
        password TEXT NOT NULL,
        PRIMARY KEY(id)
    )");

    $db->query("CREATE TABLE IF NOT EXISTS messages (
        id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
        id_author BIGINT UNSIGNED NOT NULL,
        text VARCHAR(280) NOT NULL,
        PRIMARY KEY(id)
    )");

    $db->query("CREATE TABLE IF NOT EXISTS online (
        id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
        ip VARCHAR(250) NOT NULL,
        id_actif BIGINT UNSIGNED NOT NULL,
        time BIGINT UNSIGNED NOT NULL,
        PRIMARY KEY(id)
    )");
?>