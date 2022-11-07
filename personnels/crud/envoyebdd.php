aaaaa<?php
include ("../../DAO.php");
$aRajouter = "";
$array = (array) $_GET;
if ($_GET['exec'] == "creation") {
    $aRajouter ="(`id_P`, `nom_P`, `prenom_P`, `num_Tel_P`, `email_P`, `mdpSHA256`, `fk_Cam_P`, `fk_R_P`, `active`) VALUES (NULL,'". $_GET['nom']."','".$_GET['prenom']."','".$_GET['tel']."','".$_GET['mail']."','".$_GET['mdp']."',".$_GET['campings'].",".$_GET['roles'];
    $sql = "INSERT INTO ".$_GET["table"].$aRajouter.",1);"; 
    $bdd->query($sql);
    $sqLog= "INSERT INTO `logs` (`fk_P_L`, `date_L`, `requetes_L`) VALUES ('11', 	
    NOW(), 'test');";
    //$bdd->query($sqLog);
}elseif($_GET['exec'] == "suppression"){
    $sql = "UPDATE `".$_GET["table"]."` SET `active` = '2' WHERE `personnels`.`id_P` = ". $_GET['id_P'].";"; 
    var_dump($sql);
    $bdd->query($sql);
    $sqLog= "INSERT INTO `logs` (`fk_P_L`, `date_L`, `requetes_L`) VALUES ('11', 	
    NOW(), 'test');";
    //$bdd->query($sqLog);
}

?>

<html>
 <head>
<title>Redirection en htm</title>
<meta http-equiv="refresh" content="5; URL=http://testcoordo/personnels/">
</head>
<body>
 Redirection vers www.manouvelleadresse.com dans 5 secondes.
 </body>
</html>