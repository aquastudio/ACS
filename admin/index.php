<?php

    session_start();
    include_once "../php/included_scripts/main_function.php";

    if(!isset($_SESSION["id"]) OR empty($_SESSION["id"])) {
        redirect("../index.php");
    } else {
        
        include "../php/included_scripts/connection_db.php";

        $id = $_SESSION["id"];
        $result = $db->query("SELECT * FROM members WHERE id = $id");
        $userinfo = $result->fetch_assoc();

        if ($userinfo["grade"] == "User" OR $userinfo["grade"] == "Collaborater") {
            
            $db->close();
            redirect("../public/index.php");

        }

    }

    if(isset($_POST["DM_submit"])) {
        $DM_id = intval($_POST["DM_id"]);

        if(!empty($DM_id)) {

            if($DM_id >= 1) {

                $res = $db->query("SELECT id FROM members WHERE id = '$DM_id'");
                if($res->num_rows == 1) {
                    $db->query("DELETE FROM members WHERE id = '$DM_id'");
                }

            } else {
            }

        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aqua Communication System</title>

    <link rel="stylesheet/less" href="../ui/css/layout.less"/>

    <!-- Importation de Less : -->
    <script type="text/javascript" src="../ui/plugins/Less/less.min.js"></script>
    <script type="text/javascript" src="../ui/plugins/jQuery/jQuery.min.js"></script>

</head>
<body id="admin">
    <img src="../members/avatars/<?php echo $userinfo["avatar"]; ?>" width="20" height="20"/><h1>Administrateur</h1>
    <a href="../php/logout.php">Se Déconnecter</a>

    <hr/>
    <div id="membersList">
        <?php
            $res = $db->query("SELECT * FROM members");
        ?>
        <h1>Membres totaux (<?php echo $res->num_rows; ?>)</h1>
        <h1>Membres actifs (<span id="nb_actives"></span>)</h1>
            <?php        
                while($user = $res->fetch_assoc()) { ?>
            <div>
                <img src='../members/avatars/<?php echo $user["avatar"]; ?>' width='20' height='20' /><?php echo $user["name"] ?>    
                <form action="" method="POST" name="ZM_form">
                    <input type="hidden" name="ZM_id" value="<?php echo $user['id']; ?>"/>
                    <input type="submit" name="ZM_submit" value="Details" disabled="disabled"/>
                </form>
                <form action="" method="POST" name="EM_form">
                    <input type="hidden" name="EM_id" value="<?php echo $user['id']; ?>"/>
                    <input type="submit" name="EM_submit" value="Éditer" disabled="disabled"/>
                </form>

                        <?php
                            if($user["id"] != $_SESSION["id"]) {
                        ?>
                <form action="" method="POST" name="DM_form">
                    <input type="hidden" name="DM_id" value="<?php echo $user['id']; ?>"/>
                    <input type="submit" name="DM_submit" value="Supprimer"/>
                </form>    
            </div>
                    <?php 
                        }
                }
            ?>
    </div>

        <hr/>

        <h1>Messages :</h1>
    <div id="messages"></div>
    <hr/>
    <form action="" method="POST" name="S_form" id="S_form">
        <label for="S_text">
            <textarea name="S_text" id="S_text" placeholder="Votre message..." cols="100"></textarea>
        </label><br>
        <input type="submit" name="S_submit" id="S_submit" value="Envoyer"/>
        <br/>
        <?php
            if(isset($S_message)) {
                echo $S_message;
            } else {
                echo "<br/>";
            }
        ?>
    </form>
    <script type="text/javascript">
        $("#messages").load("../php/included_scripts/show_message.php");
        setInterval(function() {
            $("#messages").load("../php/included_scripts/show_message.php");
        }, 3000);
        
        $("#nb_actives").load("../php/included_scripts/actives_system.php");
        setInterval(function() {
            $("#nb_actives").load("../php/included_scripts/actives_system.php");
        }, 15000);
    </script>  
    <script type="text/javascript" src="../ui/js/main.js"></script>
</body>
</html>