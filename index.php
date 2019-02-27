<?php

    session_start();
    require_once "php/included_scripts/main_function.php";

    if(isset($_SESSION["id"])) {
        
        redirect("public/index.php");
    }


    $L_message = "<a href='?p=signup'>Pas encore membre ?</a>";
    $R_message = "<a href='?p=login'>J'ai déjà un compte</a>";


    if(isset($_POST["R_submit"]) AND isset($_POST["R_pass"]) AND isset($_POST["R_vpass"])) {

        $R_name = asciispecialchars(ucfirst($_POST["R_name"]));
        $R_pass = $_POST["R_pass"];
        $R_vpass = $_POST["R_vpass"];

        if(!empty($R_name) AND !empty($R_pass) AND !empty($R_vpass)) {
            
            if(strlen($_POST["R_name"]) >= 3 AND strlen($_POST["R_name"]) <= 32) {

                if($R_pass == $R_vpass) {

                    unset($R_vpass);

                    if(strlen($R_pass) >= 8 AND strlen($R_pass) <= 32) {

                        include "php/included_scripts/connection_db.php";

                        $R_hpass = sha1($R_pass);
                        
                        $res = $db->query("SELECT * FROM members WHERE name = '$R_name' AND password = '$R_hpass'");
                        
                        if($res->num_rows == 0) {
                            
                            $db->query("INSERT INTO members(name, password) VALUES ('$R_name', '$R_hpass')");
                            $res = $db->query("SELECT id,grade FROM members WHERE name = '$R_name' AND password = '$R_hpass'");
                            $userinfo = $res->fetch_assoc();
                            $_SESSION["id"] = $userinfo["id"];

                            redirect("public/index.php");

                        } else {
                            $R_message = "Le nom ou le mot de passe incorrecte";
                        }
                        $db->close();

                    } else {
                        $R_message = "Les mots de passe doivent être compris entre 8 et 32 caractères";
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

    
    if(isset($_POST["L_submit"]) AND isset($_POST["L_name"]) AND isset($_POST["L_pass"])) {
        $L_name = asciispecialchars(ucfirst($_POST["L_name"]));
        $L_pass = $_POST["L_pass"];

        if(!empty($L_name) AND !empty($L_pass)) {

            if(strlen($_POST["L_name"]) >= 3 AND strlen($_POST["L_name"]) <= 32) {

                if(strlen($L_pass) >= 8 AND strlen($L_pass) <= 32) {

                    include_once "php/included_scripts/connection_db.php";

                    $L_hpass = sha1($L_pass);
                    $result = $db->query("SELECT * FROM members WHERE name = '$L_name' AND password = '$L_hpass'");

                    $nb_lines_affected = $db->affected_rows;

                    if($nb_lines_affected == 1) {

                        $userinfo = $result->fetch_assoc();
                        $_SESSION["id"] = $userinfo["id"];
                        
                        $db->close();
                        redirect("public/index.php");

                    } else {
                        $db->close();
                        $L_message =  "Nom ou Mot de Passe incorrect";
                    }

                } else {
                    $L_message = "Mot de pass trop court ou trop long (entre 8 te 32 caractères)";
                }
            } else {
                $L_message = "Nom trop court ou trop long (entre 3 te 32 caractères)";
            }
        } else {
            $L_message = "Tous les champs doivent être compétés";
        }
    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <title>Aqua Communication System</title>

    <!-- Importation less/css -->
    <link rel="stylesheet/less" href="ui/css/layout.less"/>

    <!-- Importation de Less : -->
    <script type="text/javascript" src="ui/plugins/Less/less.min.js"></script>
    <!-- Importation de jQuery -->
    <script type="text/javascript" src="ui/plugins/jQuery/jQuery.min.js"></script>

    <!-- Importation javascript -->
    <script type="text/javascript" src="ui/js/main.js"></script>
</head>
<body>
    <br/><br/><br/><br/>
    <?php
    
        if(isset($_GET["p"])) {
            $page = strval($_GET["p"]);
        } else {
            $page = "home";
        }

        switch ($page) {
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
            
            case "login":
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
            default:
                ?>
                <header>
                    <button><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?p=login">Se Connecter</a></button>
                    <button><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?p=signup">S'inscrire</a></button>
                </header>
                <h1>Accueil</h1>
                <h3>Bienvenue sur Aqua...
                <?php
                break;
        }
    ?>
    <script type="text/javascript" src="ui/js/main.js"></script>
    <script type="text/javascript" src="ui/js/main_function.js"></script>
</body>
</html>