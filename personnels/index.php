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

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="style.css" rel="stylesheet" type="text/css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <title>Personnels</title>
    </head>

    <body>
        <?php

        ?>
        <div class='container border border-secondary' style="
    padding: 0px">
            <?php
            // si c'est un responsable d'animation/ un coordinateur ou un adminTSA on laisse un bouton création personne
            if (in_array($_SESSION['Personnel']->getRole()->getLibelleRole(), array("Responsable d'Animation","Coordinateur", "Administration TSA"))) {
                echo $perso->BtnModalPerso("create","Création d'un Personnel","btn-primary col-lg-5","");
                echo $perso->ModalPerso("create","Création d'un Personnel","","","","","","","");
                //$perso->ModalCreationPerso();
            }
            //si un choix a déjà été fait
            if (!empty($_POST["campings"])) {
                $_SESSION["camp"] = $_POST["campings"];
            }
            //si c'est un animateur / un responsable d'animation / un admin Camping
            if (in_array($_SESSION['Personnel']->getRole()->getLibelleRole(), array("Responsable d'Animation","Animateur", "Administration Camping"))) {
                $perso->toutPersonnelsCamping($_SESSION["camp"]);
            } elseif (in_array($_SESSION['Personnel']->getRole()->getLibelleRole(), array("Coordinateur", "Administration TSA"))) {//si c'est un coordo on lui met un choix de camping
                echo '<br><br>
                <form action="" method="post">
                <div class="row">
                    <div class="col text-center">
                        <label for="campings-select">Camping </label> ';

                           echo $perso->ChoixCampingsCreationPerso($_SESSION["camp"]);

                echo'
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </div>
                </div>
                </form>
                <br><br>';

                $perso->toutPersonnelsCamping($_SESSION["camp"]);
            }


            ?>
            </div>
        <br>

    </body>