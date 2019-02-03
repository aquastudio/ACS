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

        } else {
            $id = $_SESSION["id"];
            include_once "../php/connection_db.php";

            if(isset($_POST["S_submit"])) {

                $S_text = strip_tags($_POST["S_text"], "<i></i><b></b><u></u><s></s><a></a>");
                
                if(!empty($S_text)) {
                    $db->query("INSERT INTO messages (id_author, text) VALUES ($id, '$S_text')");
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
    <img src="../ui/avatars/<?php echo $userinfo["avatar"]; ?>" width="20" height="20"/><h1>Administrateur</h1>
    <a href="../php/logout.php">Se Déconnecter</a>

    <div id="messages">
    </div>
    <form action="" method="POST" name="S_form" id="S_form">
        <label for="S_text">
            <textarea name="S_text" id="S_text" placholder="Aa"></textarea>
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
        $("#messages").load("../php/message.php");
        setInterval(function() {
            $("#messages").load("../php/message.php");
        }, 3000);
        
    </script>  
    <!-- <script type="text/javascript" src="../ui/js/main.js"></script> -->
</body>
</html>