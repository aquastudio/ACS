<?php

    session_start();
    include_once "../php/main_function.php";

    if(!isset($_SESSION["id"]) OR empty($_SESSION["id"])) {
        header("Location: ../index.php");
    } else {
        include_once "../php/connection_db.php";

        $id = $_SESSION["id"];
        $result = $db->query("SELECT * FROM members WHERE id = $id");
        $userinfo = $result->fetch_assoc();

        if ($userinfo["grade"] == "Admin") {
            
            $db->close();
            header("Location: ../admin/index.php");

        }
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
    Ce site est en construction, certaines fonctions ne sont pas encore opérationnelles...<br/>
    <a href="../php/logout.php">Se Déconnecter</a>
</body>
</html>