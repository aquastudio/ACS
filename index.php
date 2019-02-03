<?php

    session_start();

    if(isset($_SESSION["id"])) {
        include_once "php/connection_db.php";

        $id = $_SESSION["id"];
        $result = $db->query("SELECT * FROM members WHERE id = $id");
        $userinfo = $result->fetch_assoc();
        $db->close();
        
        if($userinfo["grade"] == "Admin") {

            header("Location: admin/index.php");

        } elseif ($userinfo["grade"] == "User" OR $userinfo["grade"] == "Collaborater") {

            header("Location: public/index.php");
        } else {
            header("Location: php/logout.php");
        }
    }

    $L_message = "<br/>";

    if(isset($_POST["L_submit"])) {
        $L_name = htmlspecialchars($_POST["L_name"]);
        $L_pass = $_POST["L_pass"];

        if(!empty($L_name) AND !empty($L_pass)) {

            if(strlen($L_name) >= 3 AND strlen($L_name) <= 32) {

                if(strlen($L_pass) >= 3 AND strlen($L_pass) <= 32) {

                    include_once "php/connection_db.php";

                    $L_hpass = sha1($L_pass);
                    $result = $db->query("SELECT * FROM members WHERE name = '$L_name' AND password = '$L_hpass'");

                    $nb_lines_affected = $db->affected_rows;

                    if($nb_lines_affected == 1) {

                        $userinfo = $result->fetch_assoc();
                        $_SESSION["id"] = $userinfo["id"];
                        
                        if($userinfo["grade"] == "Admin") {

                            $db->close();
                            header("Location: admin/index.php");

                        } else {
                            $db->close();
                            header("Location: public/index.php");
                        }
                    } else {
                        $db->close();
                        $L_message =  "Nom ou Mot de Passe incorrect";
                    }

                } else {
                    $L_message = "Mot de pass trop court ou trop long";
                }
            } else {
                $L_message = "Nom trop court ou trop long";
            }
        } else {
            $L_message = "Tous les champs doivent être compétés";
        }
    }

    include_once "php/connection_db.php";

    $psw = sha1("yh9vnrz3");

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <title>Aqua Communication System</title>

    <link rel="stylesheet/less" href="ui/css/layout.less"/>

    <!-- Importation de Less : -->
    <script type="text/javascript" src="ui/plugins/Less/less.min.js"></script>
    <script type="text/javascript" src="ui/plugins/jQuery/jQuery.min.js"></script>
    <script type="text/javascript" src="ui/js/main.js"></script>
</head>
<body>
    <br/><br/><br/><br/>
    <h2> --- Connexion : --- </h2>

    <form action="" method="POST" name="L_form" id="L_form">
        <label for="L_name">
            <b>Nom :</b>
            <input type="text" name="L_name" id="L_name"/>
        </label>
        <label for="L_pass">
            <b>Mot de Passe :</b>
            <input type="password" name="L_pass" id="L_pass"/>
        </label>
        <div id="L_message">
            <?php
                echo $L_message;
            ?>
        </div>
        <input type="submit" name="L_submit" id="L_submit">
    </form>
</body>
</html>