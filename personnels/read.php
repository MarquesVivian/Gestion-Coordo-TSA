
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
    if(!empty($_GET["id_P"])){
        $_SESSION["pChoisi"] = $_GET["id_P"];
    }
     
    if(!empty($_POST["exec"])){
        if($_POST["exec"] == "LinkAn"){
            // var_dump($_POST);
            echo LinkAnimationToPersonnel();
            $_POST = null;
            
        }elseif($_POST["exec"] == "delete_Animation"){
            $_POST = null;
        }
    }
    $calendrier->afficheTableau($_SESSION["pChoisi"],$_SESSION["camp"]);
} else {
    header("Location: http://testcoordo/personnels/");
}


    echo'<div class="row" style="height: 470px;margin-right: 0px;margin-top: -24px;">
    <div class="col">';
        $perso->unPersonnels($_SESSION["pChoisi"],"style='margin-left: 25%;'");
    echo'</div>';
    
    if(!empty($_GET["update"]) ){
        if ($_GET["update"] == "update") {
            echo'<div class="col" style="width: 250px; height:100%">'. $perso->FormUpdateAnimation($_SESSION["pChoisi"],$_GET["j"],$_GET["id_An"]).'</div>';
        }
        
    }else{
        if(in_array($_SESSION['Personnel']->getRole()->getLibelleRole(), array("Responsable d'Animation","Coordinateur", "Administration TSA"))){
            echo'<div class="col" style="width: 250px; height:100%">'. $perso->FormCreateAnimation($_SESSION["pChoisi"],$_GET["j"]).'</div>';
        }
        
    }

    

    echo'<div class=" col-4 overflow-auto" style="height: 97%;width: 660px;">
    <span>'. $perso->AnimationParPersonnel($_SESSION["pChoisi"]).'</span></div>
    
    </div>';
    ?>
</body>
</html>

<?php 
$test = "<script>javascript:history.back()</script>";
//var_dump($test);
function LinkAnimationToPersonnel(){
    include("../DAO.php");
    $erreur = VerifRequest();
    // var_dump($erreur);
    if ($erreur == "") {
        $expinsert = "INSERT INTO table (`id_An`, `id_P`, `id_Act`, `dateDebut_An`, `dateFin_An`, `nb_Particpant_An`)  VALUE valeur ;";
        $insert = $expinsert;
        $animation = "(NULL, '".$_POST["id_P"]."', '".$_POST["Activite"]."', '".$_POST["DateDebut"]." ".$_POST["HDebut"].":".$_POST["MDebut"].":00', '".$_POST["DateDebut"]." ".$_POST["HFin"].":".$_POST["MFin"].":00', NULL)";
        $sql = str_replace("table", $_POST["table"], $insert);
        $sql = str_replace("valeur", $animation, $sql);
        // var_dump($sql);
        $bdd->query($sql);
        return "Tout c'est bien passé";
    }
    
    return  $erreur;
}

function VerifRequest(){
    include("../DAO.php"); 
    if($_POST["HFin"] < $_POST["HDebut"] || ($_POST["HFin"] == $_POST["HDebut"] && $_POST["MFin"] <= $_POST["MDebut"])){
        return "L'heure de fin d'animation dois etre supperieur à l'heure de début";
    }

    createArrayHoursTest($_POST["HDebut"].":".$_POST["MDebut"],$_POST["HFin"].":".$_POST["MFin"]);

    $sel = $bdd->query('SELECT `dateDebut_An`,`dateFin_An`  FROM `animations` WHERE DATE_FORMAT(`dateDebut_An`, "%Y-%m-%d") LIKE CURDATE() AND `id_P` = '.$_POST['id_P'].';');

    
    //on vérifie si les horaires de debut et de fin ne sont pas utiliser quelque part
    $animations = $sel->fetchAll();
    
    $ArrayHoursUsedForNewAnimation = createArrayHoursTest($_POST["HDebut"].":".$_POST["MDebut"],$_POST["HFin"].":".$_POST["MFin"]);
    $ArrayHoursUsedForOldAnimationsStart = CreateArrayHoursIsUsed($animations);
    
    if (!VerifIfExistInArray($ArrayHoursUsedForNewAnimation,$ArrayHoursUsedForOldAnimationsStart)) {
        return "Impossible de créer l'animation car une horraire saisie est déja utiliser quelque part";
    }

}

function VerifIfExistInArray($arrayHoursNewAnimation,$arrayHoursOldAnimations){
    for ($i=1; $i < sizeof($arrayHoursNewAnimation)-1 ; $i++) { 
            if(in_array($arrayHoursNewAnimation[$i],$arrayHoursOldAnimations)){
                // var_dump(array_search($arrayHoursNewAnimation[$i],$arrayHoursOldAnimations));
                return false;
            }
    }
    return true;

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

function CreateArrayHoursIsUsed($animations){
    $arrayHoursUsed=[];
    $i = 0;
    foreach($animations as $animation){
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
                        //$i--;
                    }
                }
                $Minute+=15;
                $i++;
            }
            $Minute = 0;
        }
    }
    return $arrayHoursUsed;
}

?>


