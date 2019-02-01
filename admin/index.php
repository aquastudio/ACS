<?php

    session_start();

    if(!isset($_SESSION["id"]) OR empty($_SESSION["id"])) {
        header("Location: ../index.php");
    } else {
        include_once "../php/connection_db.php";

        $id = $_SESSION["id"];
        $result = $db->query("SELECT * FROM members WHERE id = $id");
        $userinfo = $result->fetch_assoc();

        if ($userinfo["grade"] == "User" OR $userinfo["grade"] == "Collaborater") {
            
            $db->close();
            header("Location: ../public/index.php");

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
    <img src="../ui/avatars/<?php echo $userinfo["avatar"]; ?>" width="20" height="20"/><h1>Administrateur</h1>
    <a href="../php/logout.php">Se Déconnecter</a>

    
</body>
</html>