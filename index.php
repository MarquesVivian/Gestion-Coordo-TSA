<?php 
include("navBar.php");
if($_SESSION["connecter"]!="oui"){
    header("Location: http://testcoordo/securites/login");
    exit();
 }
header("Location: http://testcoordo/calendrier/index.php?j=".date('Y-m-d')."")
?>