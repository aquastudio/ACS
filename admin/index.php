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

            }

        }
    }

    $AC_message = "<br/>";

    if(isset($_POST["AC_submit"])) {
        $AC_name = strval(asciispecialchars($_POST["AC_name"]));

        if(!empty($AC_name)) {
            
            include "../php/included_scripts/connection_db.php";

            $delegate = "";
            if(isset($_POST["AC_users"])) {
                $AC_users = $_POST["AC_users"];
                foreach($AC_users as $value) {
                    $delegate .= intval($value) . ",";
                }
            } else {
                $res = $db->query("SELECT * FROM members WHERE grade = 'Admin'");
                while($admininfo = $res->fetch_assoc()) {
                    $delegate .= intval($admininfo["id"]) . ",";
                }
            }
            $id = $userinfo["id"];
            $delegate = substr($delegate, 0, -1);
            $db->query("INSERT INTO checklist(id_author, name, delegate) VALUES ($id, '$AC_name', '$delegate')");

        } else {
            $AC_message = "Le nome doit être complété";
        }
    }
    if(isset($_POST["DC_submit"])) {

        if(isset($_POST["DC_id"])) {

            $DC_id = intval($_POST["DC_id"]);

            include "../php/included_scripts/connection_db.php";

            $res = $db->query("SELECT * FROM checklist WHERE id = '$DC_id'");
            $checkinfo = $res->fetch_assoc();

            if($res->num_rows == 1) {
                $id_author = $checkinfo["id_author"];
                $res2 = $db->query("SELECT * FROM members WHERE id = '$id_author'");
                $checkuserinfo = $res2->fetch_assoc();

                if($checkuserinfo["id"] == $userinfo["id"]) {
                    $db->query("DELETE FROM checklist WHERE id = '$DC_id'");
                }

            }
        }
    }
    if(isset($_POST["UC_submit"])) {
        if(isset($_POST["UC_id"]) AND isset($_POST["UC_status"])) {
            $UC_id = intval($_POST["UC_id"]);
            $UC_status = $_POST["UC_status"];

            include "../php/included_scripts/connection_db.php";

            $res = $db->query("SELECT * FROM checklist WHERE id = '$UC_id'");
            $checkinfo = $res->fetch_assoc();

            $arr = explode(",", $checkinfo["delegate"]);
            $me = false;

            foreach ($arr as $value) {
                if($value == $userinfo["id"]) {
                    $me = true;
                    break;
                }
            }
            if($me) {
                if($res->num_rows == 1)  {
                    $db->query("UPDATE checklist SET status = '$UC_status' WHERE id = '$UC_id'");
                }
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
    <img src="../members/avatars/<?php echo $userinfo["avatar"]; ?>" width="20" height="20"/><?php echo $userinfo["name"]; ?><h1>Administrateur</h1>
    <a href="../php/logout.php">Se Déconnecter</a>
    <button><a href="../public/index.php">Accèder mode Utilisateur</a></button>

    <hr/>
    <div id="membersList">
        <table>
            <?php
                $res = $db->query("SELECT * FROM members");
            ?>
            Membres totaux (<?php echo $res->num_rows; ?>)
            Membres actifs (<span id="nb_actives"></span>)
                <?php        
                    while($user = $res->fetch_assoc()) { ?>
                <tr>
                    <td>
                        <img src='../members/avatars/<?php echo $user["avatar"]; ?>' width='20' height='20' /><?php echo $user["name"] ?>
                    </td>
                    <td>    
                        <form action="" method="POST" name="ZM_form">
                            <input type="hidden" name="ZM_id" value="<?php echo $user['id']; ?>"/>
                            <input type="submit" name="ZM_submit" value="Details" disabled="disabled"/>
                        </form>
                    </td>
                    <td>
                        <form action="" method="POST" name="EM_form">
                            <input type="hidden" name="EM_id" value="<?php echo $user['id']; ?>"/>
                            <input type="submit" name="EM_submit" value="Éditer" disabled="disabled"/>
                        </form>
                    </td>
                    <td>
                                <?php
                                    if($user["id"] != $_SESSION["id"]) {
                                ?>
                        <form action="" method="POST" name="DM_form">
                            <input type="hidden" name="DM_id" value="<?php echo $user['id']; ?>"/>
                            <input type="submit" name="DM_submit" value="Supprimer"/>
                        </form>    
                    </td>
                </tr>
            <?php 
                    }
                }
            ?>
        </table>
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
    <table>
        <?php
            $res = $db->query("SELECT * FROM checklist");
            while ($checkinfo = $res->fetch_assoc()) {

                $id_checkauthor = $checkinfo["id_author"];
                $res2 = $db->query("SELECT * FROM members WHERE id = '$id_checkauthor'");
                $checkuserinfo = $res2->fetch_assoc();
                unset($id_checkauthor);
                ?>
                <tr>
                    <td>
                        <input type='checkbox'disabled="disabled"/>
                    </td>
                    <td>
                        <?php
                            echo $checkinfo["name"]; 
                        ?>
                    </td>
                    <td>
                        <img src="../members/avatars/<?php echo $checkuserinfo["avatar"]; ?>" width="20" height="20"/> <?php echo $checkuserinfo["name"] ?>
                    </td>
                    <td>
                        <ul>
                            <?php
                            
                                $arr = explode(",", $checkinfo["delegate"]);
                                foreach ($arr as $key => $value) {
                                    $res2 = $db->query("SELECT * FROM members WHERE id = '$value'");
                                    $delegateinfo = $res2->fetch_assoc();
                                    ?>
                                    <li><img src="../members/avatars/<?php echo $delegateinfo["avatar"]; ?>" width="20" height="20"/><?php echo $delegateinfo["name"]; ?></li>
                                    <?php
                                }
                            
                            ?>
                        </ul>
                    </td>
                    <?php
                        $arr = explode(",", $checkinfo["delegate"]);
                        $me = false;

                        $delegate = "";
                        foreach ($arr as $key => $value) {
                            if($value == $userinfo["id"]) {
                                $me = true;
                                break;
                            }
                        }

                        if($me) {
                            ?>
                            <td>
                                <form action="" method="POST" name="UC_form">
                                    <select name="UC_status">
                                        <option value='À faire'<?php if($checkinfo["status"] == "À faire") { echo " selected='selected'"; } ?>>À faire</option>
                                        <option value='En attente' <?php if($checkinfo["status"] == "En attente") { echo " selected='selected'"; } ?>>En attente</option>
                                        <option value='Fait' <?php if($checkinfo["status"] == "Fait") { echo " selected='selected'"; } ?>>Fait</option>
                                    </select>
                                    <input type="hidden" name="UC_id" value="<?php echo $checkinfo["id"]; ?>"/>
                                    <input type="submit" name="UC_submit" value="Enregistrer"/>
                                </form> 
                            </td>
                            <?php
                        } else { ?>
                            <td></td>
                            <?php
                        }

                    ?>
                    <td>
                        <?php echo $checkinfo["id"]; ?>
                    </td>
                    <?php
                    if($checkinfo["id_author"] == $userinfo["id"]) { ?>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="DC_id" value="<?php echo $checkinfo["id"]; ?>"/>
                                <input type="submit" name="DC_submit" value="Supprimer"/>
                            </form>
                        </td>
                    <?php
                    } else { ?>
                        <td></td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
        ?>
        <tr>
            <td colspan="7">
                <h3>Ajouter une chose à faire :</h3><br/>
                <form action="" method="POST" name="AC_form">
                    <table>
                        <tr>
                            <td><input type="text" name="AC_name" placeholder="Nom"/></td>
                            <td>
                                <!-- <select name="AC_users" id="AC_users" multiple> -->
                                    <?php

                                        // include "../php/connection_db.php";
                                        $res = $db->query("SELECT * FROM members WHERE grade = 'Admin'");

                                        while ($admininfo = $res->fetch_assoc()) {
                                            ?>
                                                <input type="checkbox" value="<?php echo $admininfo["id"]; ?>" name="AC_users[]" checked="checked"/><img src="../members/avatars/<?php echo $admininfo["avatar"]; ?>" width="20" height="20"/><?php echo $admininfo["name"]; ?><br/>
                                            <?php
                                        }

                                    ?>
                                <!-- </select> -->
                            </td>
                            <td><input type="submit" name="AC_submit" value="Ajouter"/></td>
                        </tr>
                    </table>
                </form>
                <?php
                    echo $AC_message;
                ?>
            </td>
        </tr>
    </table>
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