<?php
    session_start();

    if(isset($_SESSION["id"])) {
        include_once "../php/connection_db.php";

        $id = $_SESSION["id"];

        $S_text = strip_tags($_POST["msg"], "<i></i><b></b><u></u><s></s><a></a>");
        
        if(!empty($S_text)) {
            $db->query("INSERT INTO messages (id_author, text) VALUES ($id, '$S_text')");
        }
        $db->close();
    }
?>