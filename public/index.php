<?php

    session_start();
    include_once "../php/included_scripts/main_function.php";


    if(!isset($_SESSION["id"]) OR empty($_SESSION["id"])) {
        redirect("../index.php");
    } else {
        include_once "../php/included_scripts/connection_db.php";

        $id = $_SESSION["id"];
        $result = $db->query("SELECT * FROM members WHERE id = $id");
        $userinfo = $result->fetch_assoc();

    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Aqua Communication System</title>

    <link rel="stylesheet/less" href="../ui/css/layout.less"/>

    <!-- Importation de Less : -->
    <script type="text/javascript" src="../ui/plugins/Less/less.min.js"></script>
    <script type="text/javascript" src="../ui/plugins/jQuery/jQuery.min.js"></script>
    <script type="text/javascript" src="../ui/js/main.js"></script>
</head>
<body>
    <?php
    
        if(isset($_GET["a"])) {
            $action = strval($_GET["a"]);
        } else {
            $action = "home";
        }
    
    ?>
    <?php

    switch ($action) {
        case "update":
            require "actions/update_member_data.php";
            break;

        case "profile" :
            require "actions/show_member_data.php";
            break;
        
        default: ?>

    Ce site est en construction, certaines fonctions ne sont pas encore opérationnelles...<br/>
    <?php echo $userinfo["name"]; ?>
            <br/>
            <button><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?a=profile">Mon profil</a></button>
            <button><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?a=update">Mettre à jour mes données</a></button>
            <?php
                if($userinfo["grade"] == "Admin") { ?>
            <button><a href="../admin/index.php">Mode Administratif</a></button>
                <?php
                }
            
            ?>
            <button><a href="../php/logout.php">Se Déconnecter</a></button>

            <?php
            break;
    }
    
    ?>
</body>
</html>