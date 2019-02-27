<?php

    // session_start();
    // print_r($GLOBALS["_SESSION"]["id"]); 
    include "connection_db.php";
    require_once "main_function.php";

    $user_id = $_SESSION["id"];
    $res = $db->query("SELECT * FROM members WHERE id ='$user_id'");
    $userinfo = $res->fetch_assoc();
    unset($res);

    $session_time = 30;
    $current_time = date("U");
    $user_ip = $_SERVER["REMOTE_ADDR"];
    // $user_ip = "Test";
    // $user_ip = "Test2";
    // $user_ip = "Test3";
    // $user_ip = "Test4";
    // $user_ip = "Test5";

    $res = $db->query("SELECT * FROM online WHERE ip = '$user_ip'");
    $ip_exists = $res->num_rows;

    if($ip_exists == 0 AND $userinfo["status"] != -1) {
        $db->query("INSERT INTO online(ip, id_actif, time) VALUES('$user_ip', '$user_id', '$current_time')");
    } else {
        $db->query("UPDATE online SET time = '$current_time' WHERE ip = '$user_ip'");
    }
    $db->query("UPDATE members SET status = '1' WHERE id = '$user_id'");
    
    $expired_session = $current_time - $session_time;

    $expired_actives = $db->query("SELECT * FROM online WHERE time < $expired_session");
    
    while($unactif = $expired_actives->fetch_assoc()) {
        $current_id_actif = $unactif["id_actif"];
        if($unactif["status"] != -1) {
            $db->query("UPDATE members SET status = 0 WHERE id = '$current_id_actif'");
        }
        unset($current_id_actif);
    }
    unset($expired_actives);

    $del_ip = $db->query("DELETE FROM online WHERE time < $expired_session");

    $res = $db->query("SELECT * FROM online");
?>