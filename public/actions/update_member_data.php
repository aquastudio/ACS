<?php
/*
inclu par :
    - public/index.php
    - admin/index.php
définition :
    - modifie son profil (public)
    - modifie le profil demandé (admin)
*/
    $UP_message = BR;
    $UA_message = BR;

    if(isset($_GET["id"]) AND !empty($_GET["id"]) AND intval($_GET["id"]) AND $userinfo["grade"] == "Admin") {
                
        $id = intval($_GET["id"]);

    } else {
        $id = $_SESSION["id"];
    }

    $res = $db->query("SELECT * FROM members WHERE id = $id");
    $userupdatinginfo = $res->fetch_assoc();
    
    if(isset($_FILES["UA_file"]) AND !empty($_FILES["UA_file"]["name"])) {

        $UA_file = $_FILES["UA_file"];
        $maxLen = 2097152; // 2 Mo
        $validExtensions = ["png", "jpg", "jpeg", "gif"];

        if($UA_file["size"] <= $maxLen) {
            
            $fileExtension = get_file_extension($UA_file["name"]);
            
            if(in_array($fileExtension, $validExtensions)) {
                
                $fileName = $userinfo["id"].".".$fileExtension;
                $path = "../members/avatars/".$fileName;
                $move = move_uploaded_file($UA_file["tmp_name"], $path);

                if($move) {

                    $db->query("UPDATE members SET avatar = '$fileName' WHERE id = '$id'");
                    $UA_message = parse_emoji("Malgré les apparences, votre photo de profil a bien été changée #smile;");

                } else {
                    $UA_message = "Il y a eu une erreur durant l'importation de votre photo de profil";
                }

            } else {
                $UA_message = "Votre photo de profil doit être au format png, jpg, jpeg ou gif";
            }

        } else {
            $UA_message = "Votre photo de profil ne doit pas dépasser les 2Mo";
        }

    }

    if(isset($_POST["UP_submit"]) AND isset($_POST["UP_pass"]) AND isset($_POST["UP_vpass"]) AND isset($_POST["UP_apass"])) {

        $UP_pass = $_POST["UP_pass"];
        $UP_vpass = $_POST["UP_vpass"];
        $UP_apass = $_POST["UP_apass"];

        if(!empty($UP_pass) AND !empty($UP_vpass) AND !empty($UP_apass)) {

            if($UP_pass == $UP_vpass) {

                unset($UP_vpass);
                $passlen = strlen($_POST["UP_pass"]);

                if($passlen >= 3 AND $passlen <= 32) {
                    
                    include "../php/included_scripts/connection_db.php";

                    $UP_ahpass = sha1($UP_apass);
                    $res = $db->query("SELECT * FROM members WHERE password = '$UP_ahpass' AND id = '$id'");

                    if($res->num_rows == 1) {
                        
                        $UP_hpass = sha1($UP_pass);
                        $db->query("UPDATE members SET password = '$UP_hpass' WHERE id = '$id'");
                        $UP_message = "Votre mot de passe a bien été modifié";
                    
                    } else {
                        $UP_message = "Ancien mot de passe incorrecte";
                    }
                    

                } else {
                    $UP_message = "Les mots de passe doivent contenir entre 3 et 32 charactères";
                }

            } else {
                $UP_message = "Les mots de passe doivent être identiques";
            }

        } else {
            $UP_message = "Tous les champs doivent être complétés";
        }

    }
?>
<button><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?a=home">Retour</a></button>
<h5>Changer ma photo de profil : <img src="../members/avatars/<?php echo $userupdatinginfo["avatar"]; ?>" width="30" height="30" style="position: absolute;"/> </h5>
<form action="" method="POST" name="UA_form" id="UA_form" enctype="multipart/form-data">
    <label for="UA_file">
        <input type="file" name="UA_file" id="UA_file"/>
    </label>
    <?php
        echo $UA_message;
    ?>
    <br/>
    <input type="submit" name="UA_submit" value="Mettre à jour"/>
</form>
<h5>Changer mon mot de passe :</h5>
<form action="" method="POST" id="UP_form" name="UP_form">
    <label for="UP_apass">
        <b>Ancien mot de passe :</b>
        <input type="password" name="UP_apass" id="UP_apass">
    </label>
    <label for="UP_pass">
        <b>Création du nouveau mot de passe :</b>
        <input type="password" name="UP_pass" id="UP_pass">
    </label>
    <b>Confirmation du nouveau mot de passe :</b>
    <label for="UP_vpass">
        <input type="password" name="UP_vpass" id="UP_vpass">
    </label>
    <br/>
    <?php
        echo $UP_message;
    ?>
    <br/>
    <input type="submit" name="UP_submit" value="Mettre à jour"/>
</form>