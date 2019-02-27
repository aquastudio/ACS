<?php
/*
inclu par :
    - admin/index.php ; PHP
définition :
    affiche la liste des members et leurs "options" (supprimer, Éditer)
*/

    if(isset($_POST["DM_submit"]) AND isset($_POST["DM_id"])) {
        $DM_id = intval($_POST["DM_id"]);

        if(!empty($DM_id)) {

            if($DM_id >= 1) {

                $res = $db->query("SELECT * FROM members WHERE id = '$DM_id'");
                $userdeletedinfo = $res->fetch_assoc();

                if($res->num_rows == 1) {

                    $db->query("UPDATE members SET avatar = 'deleted.png' WHERE id = '$DM_id'");
                    $db->query("UPDATE members SET description = '' WHERE id = '$DM_id'");
                    $db->query("UPDATE members SET password = '' WHERE id = '$DM_id'");
                    $db->query("UPDATE members SET status = '-1' WHERE id = '$DM_id'");

                }

            }

        }

    }

?>
<div id="membersList">
    <table width="100%">
        <?php
            include "../php/included_scripts/actives_system.php";
            $nb_actives = $res->num_rows;
            unset($res);
            $res2 = $db->query("SELECT * FROM members WHERE 1");
        ?>
        <caption>Membres actifs <?php echo $nb_actives; ?>/<?php echo $res2->num_rows; ?></caption>
        <tbody>
            <?php        
                while($user = $res2->fetch_assoc()) { ?>
            <tr>
                <td>
                    <img src='../members/avatars/<?php echo $user["avatar"]; ?>' class="avatars" width='17' height='17' />
                    <?php echo $user["name"]; ?>
                </td>               

                <?php
                    if($user["status"] != -1) { ?>                    
                <td>
                    <button><a href="../public/index.php?a=profile&id=<?php echo $user["id"]; ?>">Profil</a></button>
                </td>
                    <?php
                        if($user["grade"] != "Admin") { ?>
                <td>
                    <button><a href="../public/index.php?a=update&id=<?php echo $user["id"]; ?>">Éditer</a></button>
                </td>
                            <?php
                            if($user["id"] != $userinfo["id"]) { ?>

                <td align="center">
                    <form method="POST" action="">
                        <input type="hidden" name="UG_id" value="<?php echo $user['id']; ?>"/>
                        <select name="UG_grade" id="UG_grade">
                            <option <?php if($user["grade"] == "User") { echo "selected"; } ?> value="User">Utilisateur</option>
                            <option <?php if($user["grade"] == "Collaborater") { echo "selected"; } ?> value="Collaborater">Collaborateur</option>
                        </select>
                    </form>
                </td>
                <td>
                    <form action="" method="POST" name="DM_form">
                        <input type="hidden" name="DM_id" value="<?php echo $user['id']; ?>"/>
                        <input type="submit" name="DM_submit" value="Supprimer"/>
                    </form>
                </td>
                            <?php
                            }
                        }
                    }
                ?>
            </tr>
            <?php
                }
            ?>
        </tbody>

    </table>
</div>
<script>
    var gradeList = document.getElementsByName("UG_grade");
    for (var i = 0; i < gradeList.length; i++) {
        gradeList[i].addEventListener("change", function(e) {
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

            xhr.open("POST", "sections/async/update_grade_member.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("UG_grade=" + this.value + "&UG_id=" + this.previousElementSibling.value);

            return false;
        }, false);
    }
</script>