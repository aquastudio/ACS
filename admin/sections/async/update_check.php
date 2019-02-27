<?php
/*
inclu par :
    - admin/section/checklist.php ; Ajax
définition :
    Modifie le statu d'une check
*/
    session_start();
    require "../../../php/included_scripts/connection_db.php";

    $id = $_SESSION["id"];
    $res = $db->query("SELECT * FROM members WHERE id = '$id'");
    $userinfo = $res->fetch_assoc();
    // Validation de màj d'un check :
    if(isset($_POST["UC_id"]) AND isset($_POST["UC_status"])) {

        $UC_id = intval($_POST["UC_id"]);
        $UC_status = $_POST["UC_status"];

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
        unset($me);
    }
?>