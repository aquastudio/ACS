<?php
    
    session_start();

    include "connection_db.php";

    $id = $_SESSION["id"];
    $result = $db->query("SELECT * FROM members WHERE id = $id");
    $userinfo = $result->fetch_assoc();

    $session_time = 30;
    $current_time = date("U");
    $user_ip = $_SERVER["REMOTE_ADDR"];

    $res = $db->query("SELECT * FROM online WHERE ip = '$user_ip'");
    $ip_exists = $res->num_rows;
    $user_id = $userinfo["id"];

    if($ip_exists == 0) {
        $db->query("INSERT INTO online(ip, id_actif, time) VALUES('$user_ip', '$user_id', '$current_time')");
    } else {
        $db->query("UPDATE online SET time = '$current_time' WHERE ip = '$user_ip'");
    }

    
    $session_delete_time = $current_time - $session_time;
    $del_ip = $db->query("DELETE FROM online WHERE time < $session_delete_time");

    $res = $db->query("SELECT * FROM online");
    $nb_actives = $res->num_rows;

    echo $nb_actives;
?>