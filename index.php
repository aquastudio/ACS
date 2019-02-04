<?php

    session_start();
    include_once "php/main_function.php";

    if(isset($_SESSION["id"])) {
        include_once "php/connection_db.php";

        $id = $_SESSION["id"];
        $result = $db->query("SELECT * FROM members WHERE id = $id");
        $userinfo = $result->fetch_assoc();
        $db->close();
        
        if($userinfo["grade"] == "Admin") {

            redirect("admin/index.php");

        } elseif ($userinfo["grade"] == "User" OR $userinfo["grade"] == "Collaborater") {

            redirect("public/index.php");
        } else {
            redirect("php/logout.php");
        }
    }

    $L_message = "<a href='?a=signup'>Pas encore membre ?</a>";
    $R_message = "<a href='?a=login'>J'ai déjà un compte</a>";

    if(isset($_POST["L_submit"])) {
        $L_name = htmlspecialchars(ucfirst($_POST["L_name"]));
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
                            redirect("admin/index.php");

                        } else {
                            $db->close();
                            redirect("public/index.php");
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

    if(isset($_POST["R_submit"])) {

        $R_name = htmlspecialchars(ucfirst($_POST["R_name"]));
        $R_pass = $_POST["R_pass"];
        $R_vpass = $_POST["R_vpass"];

        if(!empty($R_name) AND !empty($R_pass) AND !empty($R_vpass)) {
            
            if(strlen($R_name) >= 3 AND strlen($R_name) <= 32) {

                if($R_pass == $R_vpass) {

                    unset($R_vpass);

                    if(strlen($R_pass) >= 3 AND strlen($R_pass) <= 32) {

                        include "php/connection_db.php";

                        $R_hpass = sha1($R_pass);
                        
                        $res = $db->query("SELECT * FROM members WHERE name = '$R_name' AND password = '$R_hpass'");
                        
                        if($res->num_rows == 0) {
                            
                            $db->query("INSERT INTO members(name, password) VALUES ('$R_name', '$R_hpass')");
                            $res = $db->query("SELECT id,grade FROM members WHERE name = '$R_name' AND password = '$R_hpass'");
                            $userinfo = $res->fetch_assoc();
                            $_SESSION["id"] = $userinfo["id"];

                            if($userinfo["grade"] == "Admin") {
                                redirect("admin/index.php");
                            } elseif ($userinfo["grade"] == "User" OR $userinfo["grade"] == "Collaborater") {
                                redirect("public/index.php");
                            }

                        } else {
                            $R_message = "Le nom ou le mot de passe incorrecte";
                        }
                        $db->close();

                    } else {
                        $R_message = "Les mots de passe doivent être compris entre 3 et 32 caractères";
                    }

                } else {
                    $R_message = "Les mots de passe doivent être identiques";
                }

            } else {
                $R_message = "Le nom doit être compris entre 3 et 32 caractères";
            }

        } else {
            $R_message = "Tous les champs doivent être complétés";
        }
    }

    // include_once "php/connection_db.php";

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
    <?php
    if(isset($_GET["a"])) {
        $action = strval($_GET["a"]);
    } else {
        $action = "login";
    }

        switch ($action) {
            case "signup":
                ?>
                <h2> --- Inscription : --- </h2>

                <form action="" method="POST" name="R_form" id="R_form">
                    <label for="R_name">
                        <b>Nom :</b>
                        <input type="text" name="R_name" id="R_name"/>
                    </label>
                    <label for="R_pass">
                        <b>Création du Mot de Passe :</b>
                        <input type="password" name="R_pass" id="R_pass"/>
                    </label>
                    <label for="R_vpass">
                        <b>Confirmation du Mot de Passe :</b>
                        <input type="password" name="R_vpass" id="R_vpass"/>
                    </label>
                    <div id="R_message">
                        <?php
                            echo $R_message;
                        ?>
                    </div>
                    <input type="submit" name="R_submit" id="R_submit">
                </form>
                <?php
                break;
            
            default:
                ?>
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
                <?php
                break;
        }
    ?>
</body>
</html>