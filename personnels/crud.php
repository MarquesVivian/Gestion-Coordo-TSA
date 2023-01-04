<?php

//si c'est une création

function choiceExec(){
    include("../DAO.php");

if ($_POST['exec'] == "create") {
    return CreatePersonnel();

} elseif ($_POST['exec'] == "delete") {
    $sql = "UPDATE `" . $_POST["table"] . "` SET `active_P` = '0' WHERE `personnels`.`id_P` = " . $_POST['id_P'] . ";
            UPDATE `travaille` set `active_Tr` = '0' WHERE `personnels`.`id_P` = " . $_POST['id_P'] . ";";
    $bdd->query($sql);

}elseif ($_POST['exec'] == "update") {
    return UpdatePersonnel();
}
}

function UpdatePersonnel(){
    $verif = VerifRequest();
    $mdp = 1;
    $img = 1;

    if($verif['erreur'] == "Il manque un mot de passe"){
        $mdp = 0;
        $verif['erreur'] = "";
    }elseif($verif['upFile'] == "img/profile-user.png"){
        $img = 0;
    }
    if ($verif['erreur'] == "") {
        include("../DAO.php");

        $updateTravaille= $_POST['campings'] == $_POST['oldCamping'] ? "" : "UPDATE travaille SET active_tr = 0 WHERE id_P = ".$_POST['id_P']." ;INSERT INTO travaille (id_Cam, id_P, date_Tr) VALUE (" . $_POST['campings'] . ", " . $_POST['id_P'] . ", NOW());" ;
        $expinsert = "UPDATE table SET valeur WHERE `id_P` = " . $_POST['id_P'] . " ; ".$updateTravaille;
        $insert = $expinsert;

        

        $mdp = $mdp==1 ? ",`motDePasse = `".md5($_POST['mdp']) : "";
        $img = $img==1 ?", `photo_P` ='". $verif['upFile']:"'" ;
        
        $personnel = " `nom_P` = '" . $_POST['nom'] . "', `prenom_P` = '" . $_POST['prenom'] . "', `num_Tel_P` = '" . $_POST['tel'] . "', `email_P` = '" . $_POST['mail'] ."'". $img . $mdp ."', id_R = " . $_POST['roles'] ;
        $sql = str_replace("table", $_POST["table"], $insert);
        $sql = str_replace("valeur", $personnel, $sql);
        $travaille = "";
        echo $sql;
        $bdd->query($sql);
    }
    return  $verif['erreur'];
}



function CreatePersonnel(){
    include("../DAO.php");
    $verif = VerifRequest();
    if ($verif['erreur'] == "") {
        include("../DAO.php");
        $expinsert = "INSERT INTO table (`id_P`, `nom_P`, `prenom_P`, `num_Tel_P`, `email_P`, `photo_P`, `motDePasse`, `personnels`.`id_R`)  VALUE valeur ; 
        INSERT INTO travaille (id_Cam, id_P) VALUE (" . $_POST['campings'] . ", (Select id_P From Personnels Where email_P = '" . $_POST['mail'] . "'));";
        $insert = $expinsert;
        $personnel = "(NULL,'" . $_POST['nom'] . "','" . $_POST['prenom'] . "','" . $_POST['tel'] . "','" . $_POST['mail'] . "','" . $verif['upFile'] . "','" . md5($_POST['mdp']) . "'," . $_POST['roles'] . ")";
        $sql = str_replace("table", $_POST["table"], $insert);
        $sql = str_replace("valeur", $personnel, $sql);
        $bdd->query($sql);
        $verif['erreur'] = "Tout c'est bien passé";
    }elseif($verif['erreur'] == "Actualisation du personnel demander"){//si il n'est plus actif mais qu'on le créer a nouveau il s'active simplement
        $sql = "UPDATE `personnels` SET `active_P` = '1' WHERE email_P = '" . $_POST['mail'] . "'; ";
        $bdd->query($sql);
        $verif['erreur'] = "";
        $verif['erreur'] = "Tout c'est bien passé";
    }
    
    return  $verif['erreur'];
}

function VerifMail($mail){
    $retour = true;

    //variable de test 
    $testLenMail = str_replace(" ","",$mail);

    if (empty($mail)) {
        $retour = false;
    }elseif (strlen($mail) < 1) {
        $retour = false; 
    }elseif (strlen($testLenMail) < strlen($mail)) {
        $retour = false; 
    }
    

    return $retour;
}



function VerifRequest(){
    include("../DAO.php");
    $erreur ="";
    $_POST['nom']=ucfirst(str_replace(" ","-",$_POST['nom']));
    $_POST['prenom']=ucfirst(str_replace(" ","-",$_POST['prenom']));
    $_POST['mdp']=str_replace(" ","",$_POST['mdp']);
    
    
    
    if (empty($_POST['nom']) || strlen($_POST['nom']) < 1) {
        $erreur = "Il manque un nom";
    } else if (empty($_POST['prenom']) || strlen($_POST['prenom']) < 1) {
        $erreur = "Il manque un prenom";
    } else if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL) && !VerifMail($_POST['mail'])) {
        $erreur = "Il y a un problème avec le Mail";
    } else if (empty($_POST['mdp']) || strlen($_POST['mdp']) < 1) {
        $erreur = "Il manque un mot de passe";
    }
    

    if ($_POST['exec'] == "create") {
        $sel = $bdd->query("SELECT personnels.id_P, active_P FROM personnels WHERE email_P='" . $_POST['mail'] . "' limit 1");
    
    }elseif ($_POST['exec'] == "update") {
        $sel = $bdd->query("SELECT personnels.id_P FROM personnels WHERE email_P='" . $_POST['mail'] . "'  AND `id_P` != ".$_POST['id_P']." limit 1");
    }
    //on vérifie si le mail n'est pas déja utiliser
    
    $tab = $sel->fetchAll();
    if (count($tab) > 0) {
        if($tab[0]['active_P'] == 1){
            $erreur = "Le mail es utilisé sur un Personnel ";
        }else{
            $erreur = "Actualisation du personnel demander";
        }
        
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
                // echo "Attaque potentielle par téléchargement de fichiers.
                //         Voici plus d'informations :\n";
                $erreur = "probleme lors de la création de l'image";
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
    <title>Crud Personnel</title>
    <div class="erreur"><?php echo choiceExec() ?></div>
    <meta http-equiv="refresh" content="200; URL=http://testcoordo/personnels/">
</head>

</html>