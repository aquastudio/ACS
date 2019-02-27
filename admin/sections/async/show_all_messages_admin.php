<?php
/*
inclu par :
    - admin/sections/show_messages_admin.php ; JS setInterval
définition :
    affiche tous les messages de la table messages_admin
*/
    session_start();    

    require "../../../php/included_scripts/connection_db.php";
    require_once "../../../php/included_scripts/main_function.php";

    if(!isset($_SESSION["readed_messages_admin"])) {
        $_SESSION["readed_messages_admin"] = "";
    }
    
    $id = $_SESSION["id"];
    $res = $db->query("SELECT * FROM members WHERE id = '$id'");
    $userinfo = $res->fetch_assoc();
    unset($res);

    $res = $db->query("SELECT * FROM messages_admin WHERE 1 ORDER BY time ASC");
    if($_SESSION["readed_messages_admin"] < $res->num_rows) {
        do_song("default.wav");

        $_SESSION["readed_messages_admin"] = $res->num_rows;
    }

    $last_author = false;
    $messages = array();

    echo "<table width='100%'>";
    $i = 1;
    while($message = $res->fetch_assoc()) {
        
        $author = $message["id_author"];
        $res2 = $db->query("SELECT * FROM members WHERE id = '$author'");

        if($res2->num_rows == 1) {
            $authorinfo = $res2->fetch_assoc();

            if($message["id_author"] == $last_author) {
                array_push($messages, 
                    $message["text"] . BR
                );
            } else {
                $day = strval(strftime("%A", strtotime($message["time"] . " + 0  day")));

                if(trim($day) == strftime("%A")) {
                    $day = "Aujourd'hui ";
                } elseif ($day == strftime("%A", strtotime(" - 1 day"))) {
                    $day = "Hier ";
                }

                array_push($messages, "<td></tr>");
                array_push($messages, "
                    <tr>
                        <td align=center rowspan='2' colspan='2' style='width: 20pt; overflow: hidden;' width='50'><img src='" . $_SERVER["HTTP_ROOT"] . "/members/avatars/" . $authorinfo["avatar"] . "' width='40' height='40' style='vertical-align: top;'/></td>
                        <td colspan='20'>" . $authorinfo["name"] . "<span style='color: #56585d;'> - " . $day . strval(strftime(" à %H:%M", strtotime($message["time"] . " + 0  day"))) . "</span></td>
                    </tr>
                    <tr>
                        <td><div>" . $message["text"] . "</div>
                    ");
            }


            $last_author = $author;
        }
        $i++;
    }
    
    // $reversed_messages = array_reverse($messages);

    foreach($messages as $key => $value) {
        echo $value;
    }

    echo "</table>";
?>