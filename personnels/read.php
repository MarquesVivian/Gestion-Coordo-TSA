
    <?php
    include("../navBar.php");
    if ($_SESSION["connecter"] != "oui") {
        header("Location: http://testcoordo/securites/login");
        exit();
    }
    $perso = $_SESSION["Personnel"];
    ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet" type="text/css">
    <title>Calendrier Personnel</title>
</head>
<body>

<?php
    require_once("../calendrier/calendrier.php");
    $calendrier = new calendrier();
if (!empty($_GET["j"]) && $calendrier->isValid($_GET["j"])) {
    if(!empty($_POST["id_P"])){
        $_SESSION["pChoisi"] = $_POST["id_P"];
    }
     
    if(!empty($_POST["exec"])){
        if($_POST["exec"] == "LinkAn"){
            var_dump($_POST);
            var_dump(LinkAnimationToPersonnel());
            $_POST = null;
            
        }
    }
    $_POST = null;
    var_dump($_POST);
    $calendrier->afficheTableau($_SESSION["pChoisi"],$_SESSION["camp"]);
} else {
    header("Location: http://testcoordo/personnels/");
}


    echo'<div class="row" style="height: 470px;margin-right: 0px;">
    <div class="col position-relative">
    <div class="position-absolute bottom-0 start-50 translate-middle-x">';
        $perso->unPersonnels($_SESSION["pChoisi"]);
    echo'</div></div>';

    echo'<div class="col" style="width: 250px; height:50px">'. $perso->FormCreateAnimation($_SESSION["pChoisi"],$_GET["j"]).'</div>';

    echo'<div class=" col-4 overflow-auto" style="height: auto;width: 660px;">
    <span>'. $perso->AnimationParPersonnel($_SESSION["pChoisi"]).'</span></div>
    
    </div>';


    ?>
</body>
</html>

<?php 

function LinkAnimationToPersonnel(){
    include("../DAO.php");
    $erreur = VerifRequest2();
    if ($erreur == "") {
        $expinsert = "INSERT INTO table (`id_An`, `id_P`, `id_Act`, `dateDebut_An`, `dateFin_An`, `nb_Particpant_An`)  VALUE valeur ;";
        $insert = $expinsert;
        $animation = "(NULL, '".$_POST["id_P"]."', '".$_POST["Activite"]."', '".$_POST["DateDebut"]." ".$_POST["HDebut"].":".$_POST["MDebut"].":00', '".$_POST["DateDebut"]." ".$_POST["HFin"].":".$_POST["MFin"].":00', NULL)";
        $sql = str_replace("table", $_POST["table"], $insert);
        $sql = str_replace("valeur", $animation, $sql);
        // var_dump($sql);
        //$bdd->query($sql);
        return "Tout c'est bien passé";
    }
    
    return  $erreur;
}

function VerifRequest(){
    include("../DAO.php");
    $erreur ="";  
      if($_POST["HFin"] < $_POST["HDebut"] || ($_POST["HFin"] == $_POST["HDebut"] && $_POST["MFin"] <= $_POST["MDebut"])){
        return "L'heure de fin d'animation dois etre supperieur à l'heure de début";
      }
      $dateDebut = $_POST["DateDebut"]." ".$_POST["HDebut"].":".$_POST["MDebut"].":00";
      $dateFin = $_POST["DateDebut"]." ".$_POST["HFin"].":".$_POST["MFin"].":00";

      $sel = $bdd->query('SELECT * FROM `animations` WHERE  (`dateDebut_An` BETWEEN DATE_ADD("'.$dateDebut.'", INTERVAL 1 SECOND) AND DATE_ADD( "'.$dateFin.'", INTERVAL -1 SECOND)) OR (`dateFin_An` BETWEEN DATE_ADD("'.$dateDebut.'", INTERVAL 1 SECOND) AND DATE_ADD( "'.$dateFin.'", INTERVAL -1 SECOND));');
      //$sel = $bdd->query('SELECT * FROM `animations` WHERE  (`dateDebut_An` BETWEEN "'.$dateDebut.'" AND "'.$dateFin.'") OR (`dateFin_An` BETWEEN "'.$dateDebut.'" AND "'.$dateFin.'");');
      var_dump($sel);
        $tab = $sel->fetchAll();
    if (count($tab) > 0) {  
            return "Impossible, l'horaire es déja utilisé";
        }

    return "";
}

function VerifRequest2(){
    include("../DAO.php");
    $erreur ="";  
    if($_POST["HFin"] < $_POST["HDebut"] || ($_POST["HFin"] == $_POST["HDebut"] && $_POST["MFin"] <= $_POST["MDebut"])){
        return "L'heure de fin d'animation dois etre supperieur à l'heure de début";
    }
    // $dateDebut = $_POST["DateDebut"]." ".$_POST["HDebut"].":".$_POST["MDebut"].":00";
    // $dateFin = $_POST["DateDebut"]." ".$_POST["HFin"].":".$_POST["MFin"].":00";

    $sel = $bdd->query('SELECT `dateDebut_An`,`dateFin_An`  FROM `animations` WHERE DATE_FORMAT(`dateDebut_An`, "%Y-%m-%d") LIKE CURDATE() AND `id_P` = '.$_POST['id_P'].';');
    //$sel = $bdd->query('SELECT * FROM `animations` WHERE  (`dateDebut_An` BETWEEN "'.$dateDebut.'" AND "'.$dateFin.'") OR (`dateFin_An` BETWEEN "'.$dateDebut.'" AND "'.$dateFin.'");');
    var_dump($sel);
    //$tab = $sel->fetchAll();
    while ($animation = $sel->fetch()) {
        // var_dump($animation);
        // var_dump(explode(":", explode(" ",$animation['dateDebut_An'])[1])[0]." ".$_POST["HDebut"]);
        // var_dump($_POST["HDebut"]<=explode(":", explode(" ",$animation['dateDebut_An'])[1])[0]);

        // //vérifie si l'heure de départ n'est pas déja utiliser comme Heure de début
        // if( explode(":", explode(" ",$animation['dateDebut_An'])[1])[0]  == $_POST["HDebut"] ){
        //     if (explode(":", explode(" ",$animation['dateDebut_An'])[1])[2]  == $_POST["MDebut"]) {
        //         var_dump("c'est la meme horraire");
        //         return "L'horaire de départ es déja utilisé";
        //     }
        // }

        // //vérifie si l'heure de fin n'est pas déja utiliser comme Heure de fin
        // if(explode(":", explode(" ",$animation['dateFin_An'])[1])[0]  == $_POST["HFin"]){
        //     if (explode(":", explode(" ",$animation['dateFin_An'])[1])[2]  == $_POST["MFin"]) {
        //         var_dump("c'est la meme horraire");
        //         return "L'horaire de Fin es déja utilisé";
        //     }
        // }

        //Vérifier si l'heure de départ n'est pas entre un horraire déja utiliser




        // var_dump("avant le if");
        // if(($_POST["HDebut"]>= explode(":", explode(" ",$animation['dateDebut_An'])[1])[0] && $_POST["HDebut"]<= explode(":", explode(" ",$animation['dateFin_An'])[1])[0]) || ($_POST["HFin"]>= explode(":", explode(" ",$animation['dateDebut_An'])[1])[0] && $_POST["HDebut"]<= explode(":", explode(" ",$animation['dateFin_An'])[1])[0])){
        //     var_dump("if horaire");
        //     var_dump("------------------------------------------------Debut----------------------------------------");
        //     var_dump("horaire demander   ".$_POST["HDebut"].":".$_POST["MDebut"]);
        //     var_dump("horaire qui pose probleme   ".explode(" ",$animation['dateDebut_An'])[1]);
        //     var_dump("MDEBUT : ".$_POST["MDebut"]." debut ".explode(":", explode(" ",$animation['dateDebut_An'])[1])[1]);
        //     var_dump($_POST["MDebut"]>= explode(":", explode(" ",$animation['dateDebut_An'])[1])[1]);
        //     var_dump("MDEBUT : ".$_POST["MDebut"]." Fin ".explode(":", explode(" ",$animation['dateFin_An'])[1])[1]);
        //     var_dump($_POST["MDebut"]< explode(":", explode(" ",$animation['dateFin_An'])[1])[1]);
        //     var_dump("------------------------------------------------FIN DEBUT----------------------------------------");
        //     var_dump("------------------------------------------------FIN----------------------------------------");
        //     var_dump("MDEBUT : ".$_POST["MFin"]." debut ".explode(":", explode(" ",$animation['dateDebut_An'])[1])[1]);
        //     var_dump("MDEBUT : ".$_POST["MFin"]." Fin ".explode(":", explode(" ",$animation['dateFin_An'])[1])[1]);
        //     var_dump("------------------------------------------------FIN FIN----------------------------------------");
        //     if (($_POST["MDebut"]>= explode(":", explode(" ",$animation['dateDebut_An'])[1])[1] && $_POST["MDebut"]<= explode(":", explode(" ",$animation['dateFin_An'])[1])[1]) || ($_POST["MFin"]> explode(":", explode(" ",$animation['dateDebut_An'])[1])[1] && $_POST["MFin"]<= explode(":", explode(" ",$animation['dateFin_An'])[1])[1])) {
        //         var_dump("les horaires choisies sont utilisées à un autre endroit ");
        //         return "les horaires choisies sont utilisées à un autre endroit";
        //     }
        // }


        
    }

    return "";
}

?>


