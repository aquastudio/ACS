<?php
/*
inclu par :
    - ui/js/main.js ; Ajax
définition :
    insert un nouveau message à la table messages_admin
*/
    session_start();
    require_once "../../../php/included_scripts/main_function.php";

    if(!isset($_SESSION["readed_messages_admin"])) {
        $_SESSION["readed_messages_admin"] = "";
    }

    if(isset($_SESSION["id"])) {
        require "../../../php/included_scripts/connection_db.php";

        $id_author = $_SESSION["id"];
        $res = $db->query("SELECT * FROM members WHERE id = '$id_author'");
        $userinfo = $res->fetch_assoc();
        unset($res);

        $res = $db->query("SELECT * FROM messages_admin WHERE 1");

        $_SESSION["readed_messages_admin"] = $res->num_rows;

        $S_text = strip_tags(trim(asciispecialchars(parse_emoji($_POST["msg"]), true)), "<b></b><i></i><a></a><img>");

        if(!empty($S_text)) {
            $db->query("INSERT INTO messages_admin (id_author, text, time) VALUES ('$id_author', '$S_text', NOW())");
        }
    }
?>
<script>
</script>