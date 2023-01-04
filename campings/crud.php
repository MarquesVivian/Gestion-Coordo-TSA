<?php
    include("../navBar.php");
    //si le personnel n'est pas autorisé et qu'il n'est pas admin
    if ($_SESSION["connecter"] != "oui") {
        header("Location: http://testcoordo/securites/login");
            exit();

    }else{
        $perso = $_SESSION["Personnel"];
        if($perso->getRole()->getIdRole() != 5){
            header("Location: http://testcoordo/securites/login");
            exit();
        }
    }
    var_dump($_POST);


    function choiceExec(){
        include("../DAO.php");
    
    if ($_POST['exec'] == "create") {
        return CreateCamping();
    
    } elseif ($_POST['exec'] == "delete") {
        $sql = "UPDATE `" . $_POST["table"] . "` SET `active_Cam` = '0' WHERE `".$_POST["table"]."`.`id_Cam` = " . $_POST['id_Cam'] . ";";
        $bdd->query($sql);
    
    }elseif ($_POST['exec'] == "update") {
        return UpdateCamping();
    }
}


    // if ($_POST['exec'] == "create") {
    //     echo CreateCamping();
    // } elseif ($_POST['exec'] == "delete") {
    //     include("../DAO.php");
    //     $sql = "UPDATE `" . $_POST["table"] . "` SET `active_Cam` = '0' WHERE `".$_POST["table"]."`.`id_Cam` = " . $_POST['id_Cam'] . ";";
    //     $bdd->query($sql);
    // }elseif ($_POST['exec'] == "update") {
    //     echo UpdateCamping();
    // }
    // header("Location: http://testcoordo/campings/");

    function UpdateCamping(){
        $verif = VerifRequest();
        if ($verif == "") {
            include("../DAO.php");
        $sql ="UPDATE `".$_POST['table']."` SET `nom_Cam` = '".$_POST["nom_Cam"]."' WHERE `".$_POST['table']."`.`id_Cam` =". $_POST['id_Cam'] .";";
        var_dump($sql);

        $bdd->query($sql);
        }
        
    }

    function CreateCamping(){
        $verif = VerifRequest();
        if ($verif == "") {
            include("../DAO.php");
            $expinsert = "INSERT INTO table (`id_Cam`, `nom_Cam`)  VALUE valeur ";
            $insert = $expinsert;
            $valeur = "(NULL,'" . $_POST['nom_Cam'] . "')";
            $sql = str_replace("table", $_POST["table"], $insert);
            $sql = str_replace("valeur", $valeur, $sql);

            $bdd->query($sql);
            var_dump($sql);
        }
        return  $verif;
    }



    function VerifRequest(){
        include("../DAO.php");
        $erreur ="";
        
        
        
        if (empty($_POST['nom_Cam']) || $_POST['nom_Cam'] == "" || strlen($_POST['nom_Cam']) < 3) {
            $erreur = "Il manque un nom de camping";
        }else{
            //on vérifie si le role n'est pas déja utiliser
            $sel = $bdd->query("SELECT * FROM `".$_POST['table']."` WHERE `nom_Cam`='" . $_POST['nom_Cam'] . "' limit 1");
            $tab = $sel->fetchAll();
            if (count($tab) != 0) {
                $erreur = "Campings déja Existant";
            }
        }
        

        return ($erreur);
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
    <meta http-equiv="refresh" content="5; URL=http://testcoordo/campings/">
</head>

</html>