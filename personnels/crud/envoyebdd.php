<?php
include("../../DAO.php");
//si c'est une création
if ($_POST['exec'] == "creation") {
    CreatePersonnel();
    //si c'est une suppression
} elseif ($_POST['exec'] == "suppression") {
    $sql = "UPDATE `" . $_POST["table"] . "` SET `active` = '0' WHERE `personnels`.`id_P` = " . $_POST['id_P'] . ";";
    var_dump($sql);
    //$bdd->query($sql);
    $sqLog = "INSERT INTO `logs` (`fk_P_L`, `date_L`, `requetes_L`) VALUES ('11', 	
    NOW(), 'test');";
    //$bdd->query($sqLog);
}elseif ($_POST['exec'] == "modif") {
    
}


function CreatePersonnel(){
    $verif = VerifRequest();
    if ($verif['erreur'] == "") {
        include("../../DAO.php");
        $expinsert = "INSERT INTO table (`id_P`, `nom_P`, `prenom_P`, `num_Tel_P`, `email_P`, `photo_P`, `motDePasse`, `personnels`.`id_R`)  VALUE valeur ; 
        INSERT INTO travaille (id_Cam, id_P) VALUE (" . $_POST['campings'] . ", (Select id_P From Personnels Where email_P = '" . $_POST['mail'] . "'));";
        $insert = $expinsert;
        $personnel = "(NULL,'" . $_POST['nom'] . "','" . $_POST['prenom'] . "','" . $_POST['tel'] . "','" . $_POST['mail'] . "','" . $verif['upFile'] . "','" . md5($_POST['mdp']) . "'," . $_POST['roles'] . ")";
        $sql = str_replace("table", $_POST["table"], $insert);
        $sql = str_replace("valeur", $personnel, $sql);
        $travaille = "";
        var_dump($sql);
        //$bdd->query($sql);
        $sqLog = "INSERT INTO `logs` (`fk_P_L`, `date_L`, `requetes_L`) VALUES ('11', 	
        NOW(), 'test');";
        //$bdd->query($sqLog);
    }
    return  $verif['erreur'];
}



function VerifRequest(){
    include("../../DAO.php");
    $erreur ="";
    $_POST['nom']=ucfirst(str_replace(" ","",$_POST['nom']));
    $_POST['prenom']=ucfirst(str_replace(" ","",$_POST['prenom']));
    $_POST['mail']=str_replace(" ","",$_POST['mail']);
    $_POST['mdp']=str_replace(" ","",$_POST['mdp']);
    
    
    
    if (empty($_POST['nom'])) {
        $erreur = "Il manque un nom";
    } else if (empty($_POST['prenom'])) {
        $erreur = "Il manque un prenom";
    } else if (empty($_POST['mail'])) {
        $erreur = "Il manque un Mail";
    } else if (empty($_POST['mdp'])) {
        $erreur = "Il manque un mot de passe";
    } else if (empty($_POST['roles'])) {
        $erreur = "Il manque un Role";
    } else if (empty($_POST['campings'])) {
        $erreur = "il manque un camping";
    }
    
    //on vérifie si le mail n'est pas déja utiliser
    $sel = $bdd->query("SELECT personnels.id_P FROM personnels WHERE email_P='" . $_POST['mail'] . "' limit 1");
    $tab = $sel->fetchAll();
    if (count($tab) != 0) {
        $erreur = "Mail déja utiliser";
    }
    
     //si il y a une photo
     if (!empty($_FILES["Photo"]['name']) && $erreur == "") {
        /*  pour telecharger un fichier*/
        $img = $_FILES["Photo"];
        $updir = "../img/";
        $upFile = $updir . basename($img["name"]);
        $blabla = "Select * FROM personnels WHERE photo_P = '" . $upFile . "' limit 1";
        $exp = $bdd->query($blabla);
    
        $tab = $exp->fetchAll();
        if (count($tab) == 0) {
            if (move_uploaded_file($img['tmp_name'], $upFile)) {
                echo "Le fichier est valide, et a été téléchargé
                        avec succès. Voici plus d'informations :\n";
            } else {
                echo "Attaque potentielle par téléchargement de fichiers.
                        Voici plus d'informations :\n";
            }
        } else {
            $erreur = "probleme lors de la création de l'image";
        }
    } else {
        $upFile = "img/profile-user.png";
    }
    return array('erreur'=>$erreur,'upFile'=>$upFile);
}



?>

<html>

<head>
    <style>
        .erreur {
            color: #CC0000;
            margin-bottom: 10px;
        }
    </style>
    <title>Redirection en html</title>
    <div class="erreur"><?php echo CreatePersonnel() ?></div>
    <meta http-equiv="refresh" content="100; URL=http://testcoordo/personnels/">
</head>

</html>