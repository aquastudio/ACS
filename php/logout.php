<?php 
  include_once "included_scripts/main_function.php";
  session_start();
  $_SESSION = array();
  session_destroy();
  redirect("../index.php");
?>