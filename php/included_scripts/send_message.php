<?php
    session_start();
    include_once "main_function.php";

    if(isset($_SESSION["id"])) {
        include_once "connection_db.php";

        $id = $_SESSION["id"];
        $S_text = asciispecialchars(strip_tags($_POST["msg"], "<i></i><b></b><u></u><s></s><a></a>"), true);
        
        if(!empty($S_text)) {
            $db->query("INSERT INTO messages (id_author, text) VALUES ($id, '$S_text')");
        }
        $db->close();
    }
?>