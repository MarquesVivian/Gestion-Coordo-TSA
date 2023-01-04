<html lang="fr">

<body>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../personnels/style.css" rel="stylesheet">



    <?php


    include("../navBar.php");

    require_once("calendrier.php");
    $calendrier = new calendrier();
    
    //On vérifie si $_GET["j"] existe  et qu'il est bien une date
    if (!empty($_GET["j"]) && $calendrier->isValid($_GET["j"])) {
        if (!empty($_POST["campings"])) {
            $_SESSION["camp"] = $_POST["campings"];
        }
        if (in_array($_SESSION['Personnel']->getRole()->getLibelleRole(), array("Responsable d'Animation","Animateur", "Administration Camping"))) {
            $perso->toutPersonnelsCamping($_SESSION["camp"]);
        } elseif (in_array($_SESSION['Personnel']->getRole()->getLibelleRole(), array("Coordinateur", "Administration TSA"))) {//si c'est un coordo on lui met un choix de camping
            echo '<br><br>
            <form action="" method="post">
            <div class=" container-fluid row ">
                <div class="col text-center">
                    <label for="campings-select">Camping </label> ';

                       echo $perso->ChoixCampingsCreationPerso($_SESSION["camp"]);

            echo'
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </div>
            </form>
            <br><br>';

            $calendrier->afficheTableau("",$_SESSION["camp"]);
        }
        
    } else {
        header("Location: http://testcoordo/calendrier/index?j=".date('Y-m-d')."");
    }
    //var_dump($_SERVER);
    ?>




</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>