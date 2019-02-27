<?php
    session_start();
    require_once "../../../php/included_scripts/main_function.php";

    if (isset($_POST["UG_id"], $_POST["UG_grade"])) {

        require "../../../php/included_scripts/connection_db.php";

        $id = intval($_POST["UG_id"]);
        $grade = strval($_POST["UG_grade"]);

        if($grade == "User" or $grade == "Collaborater") {

            $res = $db->query("SELECT * FROM members WHERE id = '$id'");
            $concerned = $res->fetch_assoc();

            if($res->num_rows == 1) {

                if($concerned["grade"] != $grade) {

                    $db->query("UPDATE members SET grade = '$grade' WHERE id = '$id'");

                }

            }
            unset($res);

        }
}
?>
level ajax