<?php
    //si le personnel n'est pas autorisé et qu'il n'est pas admin
    include("../navBar.php");
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

    function choiceExec(){
            include("../DAO.php");
        
        if ($_POST['exec'] == "create") {
            return CreateRole();
        
        } elseif ($_POST['exec'] == "delete") {
            $sql = "UPDATE `" . $_POST["table"] . "` SET `active_R` = '0' WHERE `".$_POST["table"]."`.`id_R` = " . $_POST['id_R'] . ";";
            $bdd->query($sql);
        
        }elseif ($_POST['exec'] == "update") {
            return UpdateRole();
        }
    }

    function UpdateRole(){
        $verif = VerifRequest();
        if ($verif == "") {
            include("../DAO.php");
        $sql ="UPDATE `roles` SET `libelle_R` = '".$_POST["libelle_R"]."' WHERE `roles`.`id_R` = 1;";
        var_dump($sql);

        $bdd->query($sql);
        }
        
    }

    function CreateRole(){
        $verif = VerifRequest();
        if ($verif == "") {
            include("../DAO.php");
            $expinsert = "INSERT INTO table (`id_R`, `libelle_R`)  VALUE valeur ";
            $insert = $expinsert;
            $valeur = "(NULL,'" . $_POST['libelle_R'] . "')";
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
        
        
        
        if (empty($_POST['libelle_R']) || $_POST['libelle_R'] == "" || strlen($_POST['libelle_R']) < 3) {
            $erreur = "Il manque un Libelle";
        }else{
            //on vérifie si le role n'est pas déja utiliser
            $sel = $bdd->query("SELECT `roles`.`id_R` FROM `roles` WHERE `libelle_R`='" . $_POST['libelle_R'] . "' limit 1");
            $tab = $sel->fetchAll();
            if (count($tab) != 0) {
                $erreur = "Rôle déja Existant";
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
    <meta http-equiv="refresh" content="5; URL=http://testcoordo/roles/">
</head>

</html>