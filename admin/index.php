<?php

    session_start();
    require_once "../php/included_scripts/main_function.php";
    
    if(!isset($_SESSION["id"]) OR empty($_SESSION["id"])) {
        redirect("../index.php");
    } else {
        
        require "../php/included_scripts/connection_db.php";

        $id = $_SESSION["id"];
        $result = $db->query("SELECT * FROM members WHERE id = $id");
        $userinfo = $result->fetch_assoc();

        if ($userinfo["grade"] == "User" OR $userinfo["grade"] == "Collaborater") {
            
            $db->close();
            redirect("../public/index.php");

        }

    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <title>Aqua Communication System</title>

    <link rel="stylesheet/less" href="../ui/css/layout.less"/>

    <!-- Importation de Less : -->
    <script type="text/javascript" src="../ui/plugins/Less/less.min.js"></script>
    <!-- Importation de jQuery : -->
    <script type="text/javascript" src="../ui/plugins/jQuery/jQuery.min.js"></script>

</head>
<body id="admin">
    <div id="activesRefresh"></div>
    <img src="../members/avatars/<?php echo $userinfo["avatar"]; ?>" class="avatars" width="17" height="17"/><?php echo $userinfo["name"]; ?>
    <h1>Administrateur</h1>
    <button><a href="../php/logout.php">Se Déconnecter</a></button>
    <button><a href="../public/index.php">Mode Utilisateur</a></button>
        <table id="mainTable">
        <tr>
            <td>
                <?php
                    require "sections/show_messages_admin.php";
                ?>
            </td>
            <td>
                <?php
                    require "sections/checklist.php";
                ?>
            </td>
        </tr>
        <tr>
            <td>    
                <?php
                    require "sections/show_all_members.php";
                ?>
            </td>
            <td align="center">
                <br/>
                Section innachevée
            </td>
        </tr>
    </table>
    <script type="text/javascript" src="../ui/js/main.js"></script>
    <script type="text/javascript" src="../ui/js/main_function.js"></script>
</body>
</html>