<?php
include ("../../DAO.php");
$aRajouter = "";
$array2 = (array) $_POST;



/*  pour telecharger un fichier
$img = $_FILES["Photo"];
var_dump($_POST);
var_dump($img);
$updir = "../img/";
$upFile = $updir . basename($img["name"]);

echo '<pre>';
if (move_uploaded_file($img['tmp_name'], $upFile)) {
    echo "Le fichier est valide, et a été téléchargé
           avec succès. Voici plus d'informations :\n";
} else {
    echo "Attaque potentielle par téléchargement de fichiers.
          Voici plus d'informations :\n";
}

echo 'Voici quelques informations de débogage :';
print_r($_FILES);
echo '</pre>';
*/

if ($_POST['exec'] == "creation") {
    $expinsert = "INSERT INTO table (`id_P`, `nom_P`, `prenom_P`, `num_Tel_P`, `email_P`, `photo_P`, `identifiant_P` , `mdp`, `fk_R_P`)  VALUE valeur";
    $insert = $expinsert;
    $personnel ="(NULL,'". $_POST['nom']."','".$_POST['prenom']."','".$_POST['tel']."','".$_POST['mail']."','".$_POST['mdp']."',".$_POST['campings'].",".$_POST['roles'].");";
    $sql = str_replace("table",$_POST["table"],$insert);
    $sql = str_replace("valeur",$personnel,$sql);
    $travaille = "";
    var_dump($sql);
    //$bdd->query($sql);
    $sqLog= "INSERT INTO `logs` (`fk_P_L`, `date_L`, `requetes_L`) VALUES ('11', 	
    NOW(), 'test');";
    //$bdd->query($sqLog);
}elseif($_POST['exec'] == "suppression"){
    $sql = "UPDATE `".$_POST["table"]."` SET `active` = '2' WHERE `personnels`.`id_P` = ". $_POST['id_P'].";"; 
    var_dump($sql);
    $bdd->query($sql);
    $sqLog= "INSERT INTO `logs` (`fk_P_L`, `date_L`, `requetes_L`) VALUES ('11', 	
    NOW(), 'test');";
    //$bdd->query($sqLog);
}



?>

<html>
 <head>
<title>Redirection en html</title>
<!--<meta http-equiv="refresh" content="50; URL=http://testcoordo/personnels/">-->
</head>
<body>
 Redirection vers www.manouvelleadresse.com dans 1 secondes.
 </body>
</html>