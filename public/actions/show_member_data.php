<?php
/*
inclu par :
    - public/index.php
    - admin/index.php
définition :
    - affiche son profil (public)
    - affiche le profil demandé (admin)
*/
if(isset($_GET["id"]) and !empty($_GET["id"]) and intval($_GET["id"]) and $userinfo["grade"] == "Admin") {
                
    $id = intval($_GET["id"]);

} else {
    $id = $_SESSION["id"];
}

$res = $db->query("SELECT * FROM members WHERE id = $id");
$usershowinginfo = $res->fetch_assoc();
?>
<img src="../members/avatars/<?php echo $usershowinginfo["avatar"]; ?>" width="50" height="50"/>