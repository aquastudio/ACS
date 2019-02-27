<?php
/*
inclu par :
    - admin/index.php ; PHP
définition :
    Affiche le chat et les message de la table messages_admin
*/
?>

<h1>Messages :</h1>
<div id="messages"></div>
<form action="" method="POST" name="S_form" id="S_form">
    <label for="S_text">
        <textarea name="S_text" id="S_text" placeholder="Votre message..." cols="100"></textarea>
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
    $("#messages").load("sections/async/show_all_messages_admin.php");
    setInterval(function() {
        $("#messages").load("sections/async/show_all_messages_admin.php");
    }, 5000);
</script>  