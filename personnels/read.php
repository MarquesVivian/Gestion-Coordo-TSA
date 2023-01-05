
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
            LinkAnimationToPersonnel();
            $_POST = null;
            
        }
    }
    $_POST = null;
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

    echo'<div class=" col-4 overflow-auto" style="height: 97%;width: 660px;">
    <span>'. $perso->AnimationParPersonnel($_SESSION["pChoisi"]).'</span></div>
    
    </div>';


    ?>
</body>
</html>

<?php 

function LinkAnimationToPersonnel(){
    include("../DAO.php");
    $erreur = VerifRequest3();
    var_dump($erreur);
    if ($erreur == "") {
        $expinsert = "INSERT INTO table (`id_An`, `id_P`, `id_Act`, `dateDebut_An`, `dateFin_An`, `nb_Particpant_An`)  VALUE valeur ;";
        $insert = $expinsert;
        $animation = "(NULL, '".$_POST["id_P"]."', '".$_POST["Activite"]."', '".$_POST["DateDebut"]." ".$_POST["HDebut"].":".$_POST["MDebut"].":00', '".$_POST["DateDebut"]." ".$_POST["HFin"].":".$_POST["MFin"].":00', NULL)";
        $sql = str_replace("table", $_POST["table"], $insert);
        $sql = str_replace("valeur", $animation, $sql);
        // var_dump($sql);
        // $bdd->query($sql);
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


        $THU=[];
        for ($Heure=explode(":", explode(" ",$animation['dateDebut_An'])[1])[0], $i = 0; $Heure <= explode(":", explode(" ",$animation['dateFin_An'])[1])[0] ; $Heure++, $i++) { 
            var_dump($Heure);
            if ($Heure == explode(":", explode(" ",$animation['dateDebut_An'])[1])[0]) {
                $Minute = explode(":", explode(" ",$animation['dateDebut_An'])[1])[1];
            }else{
                $Heure = $Heure < 10 ?"0".$Heure :"".$Heure ;
                $Minute = $Minute < 10 ?"0".$Minute : "".$Minute;
            }
            
            $continue = true;
            while ( $Minute < 60 && $continue) { 
                
                $THU[$i] = $Heure.":".$Minute;

                //var_dump("heure = : ".$Heure);
                if ($Heure == explode(":", explode(" ",$animation['dateFin_An'])[1])[0]) {
                    var_dump("On rentre dans le if du Heure");
                    var_dump($Minute);
                    var_dump(explode(":", explode(" ",$animation['dateFin_An'])[1])[1]);
                    if ($Minute == explode(":", explode(" ",$animation['dateFin_An'])[1])[1]) {
                        var_dump("on rentredans le if minutes");
                        $continue = false;
                    }
                    // 
                }
                $Minute+=15;
                $i++;
            }
            $Minute = 0;
        }
        var_dump($THU);
    }

    return "";
}


function VerifRequest3(){
    include("../DAO.php"); 
    if($_POST["HFin"] < $_POST["HDebut"] || ($_POST["HFin"] == $_POST["HDebut"] && $_POST["MFin"] <= $_POST["MDebut"])){
        return "L'heure de fin d'animation dois etre supperieur à l'heure de début";
    }

    createArrayHoursTest($_POST["HDebut"].":".$_POST["MDebut"],$_POST["HFin"].":".$_POST["MFin"]);

    $sel = $bdd->query('SELECT `dateDebut_An`,`dateFin_An`  FROM `animations` WHERE DATE_FORMAT(`dateDebut_An`, "%Y-%m-%d") LIKE CURDATE() AND `id_P` = '.$_POST['id_P'].';');

    
    //on vérifie si les horaires de debut et de fin ne sont pas utiliser quelque part
    $animations = $sel->fetchAll();
    $valide = CreateArrayHoursIsUsed($animations,$_POST["HDebut"].":".$_POST["MDebut"],"dateFin_An");

    if ($valide) {
        return $_POST["HDebut"]."H".$_POST["MDebut"]." es déjà utiliser";
        var_dump($_POST["HDebut"].":".$_POST["MDebut"]." n'est pas valide");
    }
$valide = CreateArrayHoursIsUsed($animations,$_POST["HFin"].":".$_POST["MFin"],"dateDebut_An");
    if ($valide) {
        return $_POST["HFin"]."H".$_POST["MFin"]." es déjà utiliser";
        var_dump($_POST["HFin"].":".$_POST["MFin"]." n'est pas une horraire valide");
    }
}

function createArrayHoursTest($Hdebut,$Hfin){
    //on vas créer 2 tableau, un avec l'heure de debut et l'autre avec l'heure de fin

    $Minute="";
    $tableauHAnimation = [];
    
    for ($Heure=explode(":", $Hdebut)[0], $i = 0; $Heure <= explode(":", $Hfin)[0] ; $Heure++) { 
        if ($Heure == explode(":", $Hdebut)[0]) {
            $Minute = explode(":", $Hdebut)[1];
        }else{
            $Heure = $Heure < 10 ?"0".$Heure :"".$Heure ;
            $Minute = $Minute < 10 ?"0".$Minute : "".$Minute;
        }
            
        $continue = true;
        while ( $Minute < 60 && $continue) { 
            $tableauHAnimation[$i] = $Heure.":".$Minute;
            var_dump($Minute);
            if ($Heure == explode(":", $Hfin)[0]) {
                if ($Minute == explode(":", $Hfin)[1]) {
                    $continue = false;
                    $i--;
                }
            }
            $Minute+=15;
            $i++;
        }
        $Minute = 0;
    }
    return $tableauHAnimation;

}

function CreateArrayHoursIsUsed($animations,$horraireAVerifier,$startOrEnd){
    var_dump("on vérifie si : ".$horraireAVerifier." es une horraire valide");
    $arrayHoursUsed=[];
    $i = 0;
    foreach($animations as $animation){
        
        $heureASupprimer = explode(":", explode(" ",$animation[$startOrEnd])[1])[0].":".explode(":", explode(" ",$animation[$startOrEnd])[1])[1];
        $Minute="";
        for ($Heure=explode(":", explode(" ",$animation['dateDebut_An'])[1])[0]; $Heure <= explode(":", explode(" ",$animation['dateFin_An'])[1])[0] ; $Heure++) { 
            if ($Heure == explode(":", explode(" ",$animation['dateDebut_An'])[1])[0]) {
                $Minute = explode(":", explode(" ",$animation['dateDebut_An'])[1])[1];
            }else{
                $Heure = $Heure < 10 ?"0".$Heure :"".$Heure ;
                $Minute = $Minute < 10 ?"0".$Minute : "".$Minute;
            }
            $continue = true;
            while ( $Minute < 60 && $continue) { 
                $arrayHoursUsed[$i] = $Heure.":".$Minute;
                if ($Heure == explode(":", explode(" ",$animation['dateFin_An'])[1])[0]) {
                    if ($Minute == explode(":", explode(" ",$animation['dateFin_An'])[1])[1]) {
                        $continue = false;
                        $i--;
                    }
                }
                $Minute+=15;
                $i++;
            }
            $Minute = 0;
        }
        unset($arrayHoursUsed[array_search($heureASupprimer, $arrayHoursUsed)]);
    }

    if (in_array($horraireAVerifier,$arrayHoursUsed) == false){
        return false;
    }
    return true;
}

/**
 * Ont veut créer des Animations 
 * chaque animation aura une horraire de debut et une horraire de fin
 * une horraire de debut peut etre égale a une horraire de fin d'une autre animation.
 * une horraire de fin peut être égale à une horraire de debut d'une autre animation.
 * il faut faire attention a ce que les horaire d'une animations ne sois pas déja utiliser
 * exemple : animation N°1 hdebut = 08:00 hFin=10:00 animation N°2 hDebut = 09:00 HFin = 11:00
 * 
 */
?>


