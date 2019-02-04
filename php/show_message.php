<?php

    include_once "connection_db.php";
    $res = $db->query("SELECT * FROM messages ORDER BY id DESC LIMIT 40");
    
    if(isset($res)) {

        while($message = $res->fetch_assoc()) {
            echo $message["text"]."<br/>";
        }

    }
?>