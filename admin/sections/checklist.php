<?php
/*
inclu par :
    - admin/index.php ; PHP
définition :
    gestion des checks
*/

    $AC_message = "";

    // Validation de l'ajoût d'un check:
    if(isset($_POST["AC_submit"]) AND isset($_POST["AC_name"])) {
        $AC_name = asciispecialchars(strval($_POST["AC_name"]));

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
            $AC_message = "Le nome doit être renseigné";
        }
    }

    // validation de suppression d'un check
    if(isset($_POST["DC_submit"]) AND isset($_POST["DC_id"])) {

        $DC_id = intval($_POST["DC_id"]);

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

?>
Liste des choses à faire :
<div style="height: 300px; overflow-y: scroll; box-shadow: inset 4px 2px 5px #000; padding: 7px;">
    <table width="100%">
        <?php
            $res = $db->query("SELECT * FROM checklist WHERE 1");
            while ($checkinfo = $res->fetch_assoc()) {

                $id_checkauthor = $checkinfo["id_author"];
                $res2 = $db->query("SELECT * FROM members WHERE id = '$id_checkauthor'");
                $checkuserinfo = $res2->fetch_assoc();
                unset($id_checkauthor);
                ?>
        <tr>
            <td>
                <?php
                    echo $checkinfo["name"]; 
                ?>
            </td>
            <td>
                <img src="../members/avatars/<?php echo $checkuserinfo["avatar"]; ?>" class="avatars" width="17" height="17"/> <?php echo $checkuserinfo["name"] ?>
            </td>
            <td>
                <ul>
                    <?php
                    
                        $arr = explode(",", $checkinfo["delegate"]);
                        foreach ($arr as $key => $value) {
                            $res2 = $db->query("SELECT * FROM members WHERE id = '$value'");
                            $delegateinfo = $res2->fetch_assoc();
                            ?>
                    <li><img src="../members/avatars/<?php echo $delegateinfo["avatar"]; ?>" class="avatars" width="17" height="17"/><?php echo $delegateinfo["name"]; ?></li>
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
            <td align="center">
                <form action="" method="POST" name="UC_form" id="UC_form">
                    <input type="hidden" name="UC_id" value="<?php echo $checkinfo["id"]; ?>"/>
                    <select name="UC_status" class="UC_status">
                        <option value='0' <?php if($checkinfo["status"] == 0) { echo " selected='selected' "; } ?>>À faire</option>
                        <option value='1' <?php if($checkinfo["status"] == 1) { echo " selected='selected' "; } ?>>En cours</option>
                        <option value='2' <?php if($checkinfo["status"] == 2) { echo " selected='selected' "; } ?>>Fait</option>
                    </select>
                </form> 
            </td>
                    <?php
                } else { ?>
                    <td>
                        <?php
                            switch ($checkinfo["status"]) {
                                case 0:
                        echo "À faire";
                                    break;
                                
                                case 1:
                        echo "En cours";
                                    break;
                                
                                case 2:
                        echo "Fait";
                                    break;
                            }
                        ?>
                    </td>
                    <?php
                }
                unset($me);

            ?>
            <?php
            if($checkinfo["id_author"] == $userinfo["id"]) { ?>
            <td align="center">
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
    </table>
</div>
<table width="100%">
        <tr>
            <td colspan="7">
                <form action="" method="POST" name="AC_form">
                    <table width="100%">
                        <tr>
                            <td>
                                <input type="text" name="AC_name" placeholder="Nom"/>
                            </td>
                            <td>
                                <?php

                                    $res = $db->query("SELECT * FROM members WHERE grade = 'Admin'");

                                    while ($admininfo = $res->fetch_assoc()) {
                                        ?>
                                    <label for="check<?php echo $admininfo["id"]; ?>">
                                        <input type="checkbox" value="<?php echo $admininfo["id"]; ?>" name="AC_users[]" checked="checked" id="check<?php echo $admininfo["id"]; ?>"/>
                                        <img src="../members/avatars/<?php echo $admininfo["avatar"]; ?>" class="avatars" width="17" height="17"/>
                                        <?php echo $admininfo["name"]; ?>
                                        <br/>
                                    </label>
                                        <?php
                                    }
                                    unset($res);

                                ?>
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
<script>
    var statusList = document.getElementsByClassName("UC_status");
    for (var i = 0; i < statusList.length; i++) {
        statusList[i].addEventListener("change", function(e) {
            e.preventDefault();
            
            var xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function() {
                console.log(this);
                if(this.readyState == 4 && this.status == 200) {
                    console.log(this.response);
                } else if(this.readyState == 4) {
                    alert("Une erreur est survenue...");
                }
            };
            console.log("UC_status=" + this.value + "&UC_id=" + this.previousElementSibling.value);

            xhr.open("POST", "sections/async/update_check.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("UC_status=" + this.value + "&UC_id=" + this.previousElementSibling.value);

            return false;
        }, false);
    }
</script>