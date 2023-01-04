<?php
    include("../navBar.php");
    //si le personnel n'est pas autorisé et qu'il n'est pas admin
    if ($_SESSION["connecter"] != "oui") {
        header("Location: http://testcoordo/securites/login");
            exit();

    }else{
        $perso = $_SESSION["Personnel"];
        if($perso->getRole()->getLibelleRole() != "Administration TSA"){
            header("Location: http://testcoordo/securites/login");
            exit();
        }
    }
    var_dump($_POST);

    function choiceExec(){
        include("../DAO.php");
    
    if ($_POST['exec'] == "create") {
        return CreateActivites();
    
    } elseif ($_POST['exec'] == "delete") {
        $sql = "UPDATE `" . $_POST["table"] . "` SET `active_Act` = '0' WHERE `".$_POST["table"]."`.`id_Act` = " . $_POST['id_Act'] . ";";
        $bdd->query($sql);
    
    }elseif ($_POST['exec'] == "update") {

        return UpdateActivites();
    }
}

    function UpdateActivites(){
        $verif = VerifRequest();
        var_dump($verif);
        if ($verif == "") {
            include("../DAO.php");
        $sql ="UPDATE `".$_POST['table']."` SET `libelle_Act` = '".$_POST["libelle_Act"]."' , `description_Act` = '".$_POST['description_Act']."', `couleur_Act` = '".$_POST['couleur_Act']."', `id_Cam` = '".$_POST['campings']."' WHERE `".$_POST['table']."`.`id_Act` =". $_POST['id_Act'] .";";


        $bdd->query($sql);
        }
        
    }

    function CreateActivites(){
        var_dump("test");
        $verif = VerifRequest();
        var_dump($verif);
        if ($verif == "") {
            include("../DAO.php");
            $expinsert = "INSERT INTO table (`id_Act`, `libelle_Act`, `description_Act`, `couleur_Act`, `id_Cam`)  VALUE valeur ;";
            $insert = $expinsert;

            $valeur = "(NULL,'" . $_POST['libelle_Act'] . "','" . $_POST['description_Act'] . "','" . $_POST['couleur_Act'] . "','" . $_POST['campings'] . "')";
            $sql = str_replace("table", $_POST["table"], $insert);
            $sql = str_replace("valeur", $valeur, $sql);
            var_dump($sql);

            $bdd->query($sql);
        }
        return  $verif;
    }



    function VerifRequest(){
        include("../DAO.php");
        $erreur ="";
        
        var_dump("test");
        
        if (empty($_POST['libelle_Act']) || $_POST['libelle_Act'] == "" || strlen($_POST['libelle_Act']) < 3) {
            var_dump("test");
            $erreur = "Il manque un nom pour l'activitée";
        }else{
            $_POST['libelle_Act'] = str_replace("'","\'",$_POST['libelle_Act']);
            $_POST['description_Act'] = str_replace("'","\'",$_POST['description_Act']);
            //on vérifie si l'activité n'est pas déja utiliser

            $sel = $bdd->query("SELECT * FROM `".$_POST['table']."` WHERE `libelle_Act`='" . $_POST['libelle_Act'] . "' AND id_Cam = ".$_POST['campings']." limit 2");
            $tab = $sel->fetchAll();
            if (count($tab) >1) {
                $erreur = "activité déja existante";
            }
            var_dump("test");


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
    <title>Crud Activitées</title>
    <div class="erreur"><?php echo choiceExec() ?></div>
    <meta http-equiv="refresh" content="5; URL=http://testcoordo/activites/">
</head>

</html>