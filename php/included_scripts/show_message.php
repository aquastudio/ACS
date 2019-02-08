<?php

    include_once "connection_db.php";
    $res = $db->query("SELECT * FROM messages ORDER BY id DESC LIMIT 40");
    
    while($message = $res->fetch_assoc()) {
        
        $author = $message["id_author"];
        $res2 = $db->query("SELECT * FROM members WHERE id = '$author'");

        if($res2->num_rows == 1) {
            $authorinfo = $res2->fetch_assoc();
            echo "<img src='../members/avatars/" . $authorinfo["avatar"] . "' width='20' height='20'/> " . $message["text"] . "<br/>";
        }
    }
?>