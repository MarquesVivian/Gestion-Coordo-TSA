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

    if ($_POST['exec'] == "create") {
        echo CreateCamping();
    } elseif ($_POST['exec'] == "delete") {
        include("../DAO.php");
        $sql = "UPDATE `" . $_POST["table"] . "` SET `active_R` = '0' WHERE `".$_POST["table"]."`.`id_Act` = " . $_POST['id_Act'] . ";";
        $bdd->query($sql);
    }elseif ($_POST['exec'] == "update") {
        echo UpdateCamping();
    }
    header("Location: http://testcoordo/campings/");

    function UpdateCamping(){
        $verif = VerifRequest();
        if ($verif == "") {
            include("../DAO.php");
        $sql ="UPDATE `".$_POST['table']."` SET `libelle_Act` = '".$_POST["libelle_Act"]."' , `description_Act` = '".$_POST['description_Act']."', `couleur_Act` = '#".$_POST['couleur_Act']."', `id_Cam` = '".$_POST['campings']."' WHERE `".$_POST['table']."`.`id_Act` =". $_POST['id_Act'] .";";
        var_dump($sql);

        $bdd->query($sql);
        }
        
    }

    function CreateCamping(){
        $verif = VerifRequest();
        if ($verif == "") {
            include("../DAO.php");
            $expinsert = "INSERT INTO table (`id_Act`, `libelle_Act`, `description_Act`, `couleur_Act`, `id_Cam`)  VALUE valeur ";
            $insert = $expinsert;
            $valeur = "(NULL,'" . $_POST['libelle_Act'] . "','" . $_POST['description_Act'] . "','" . $_POST['couleur_Act'] . "','" . $_POST['campings'] . "')";
            $sql = str_replace("table", $_POST["table"], $insert);
            $sql = str_replace("valeur", $valeur, $sql);

            //$bdd->query($sql);
            var_dump($sql);
        }
        return  $verif;
    }



    function VerifRequest(){
        include("../DAO.php");
        $erreur ="";
        $_POST['nom_Cam']=ucfirst(str_replace(" ","",$_POST['nom_Cam']));
        
        
        
        if (empty($_POST['nom_Cam']) || $_POST['nom_Cam'] == "") {
            $erreur = "Il manque un nom de camping";
        }else{
            //on vérifie si le role n'est pas déja utiliser
            $sel = $bdd->query("SELECT * FROM `".$_POST['table']."` WHERE `nom_Cam`='" . $_POST['nom_Cam'] . "' limit 1");
            $tab = $sel->fetchAll();
            if (count($tab) != 0) {
                $erreur = "Rôle déja Existant";
            }
        }
        

        return ($erreur);
    }

?>