    <?php
    include("../navBar.php");
    if ($_SESSION["autoriser"] != "oui") {
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <title>Personnels</title>
    </head>

    <body>
        <?php
            if(in_array($_SESSION['Personnel']->getIdRole(),array(2,4,5))){
                $perso->ModalCreationPerso();
            }
        ?>
        <div class='container border border-secondary'>

            <?php

            
            if (!empty($_POST["campings"])) {
                $_SESSION["camp"] = $_POST["campings"];
            }
            var_dump($_SESSION["camp"]);
            var_dump($_POST);
                if (in_array($_SESSION['Personnel']->getIdRole(), array(1, 2, 3))) {
                    $perso->toutPersonnelsCamping();
                }elseif(in_array($_SESSION['Personnel']->getIdRole(), array(4,5))){
                    
                    echo'
                    <form action="" method="post">
                    <div class="row">
                        <div class="col text-center">
                            <label for="campings-select">Camping </label> 
                            '.$perso->ChoixCampingsCreationPerso().'
                            <button type="submit" class="btn btn-primary">Envoyer</button>
                        </div>
                    </div>
                    </form>
                    <br><br>';
                    $perso->toutPersonnelsCamping($_SESSION["camp"]);
                    //$perso->toutPersonnels();
                }        
           ?>


        </div>


    </body>