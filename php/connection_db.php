<?php

    $host = "";
    $user = "";
    $pass = "";
    $base = "";

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
        text VARCHAR(280) NOT NULL,
        PRIMARY KEY(id)
    )");


?>