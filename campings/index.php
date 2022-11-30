<?php
    include("../navBar.php");
    if ($_SESSION["connecter"] != "oui") {
        header("Location: http://testcoordo/securites/login");
            exit();

    }else{
        $perso = $_SESSION["Personnel"];
        if(!in_array($perso->getRole()->getIdRole(), array(4,5))){
            header("Location: http://testcoordo/");
            exit("Vous n'avez pas l'accés a ceci");
        }
    }
    ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campings</title>
</head>

<body>
    <div class="container-fluid">
        <?php
    echo $_SESSION["Personnel"]->getAllCampings()[0]->BtnModalCamping("create","Création d'un Camping","btn-primary col-lg-5","");
    echo $_SESSION["Personnel"]->getAllCampings()[0]->ModalCamping("create","Création d'un Camping","","");
    ?>

    <div class="position-absolute border border-3 border-primary rounded-circle" style="right: 13px ; top: 100px">
        <a  href="">   
            <img src="../img/refresh.png" alt="Refresh">
        </a></div>
    <br>
    
<div class="table-responsive">
    <table class="table table-primary">
        <thead>
            <tr>
                <th scope="col" class="text-center">Id</th>
                <th scope="col" class="text-center">Libelle</th>
                <th scope="col" class="text-center">Active</th>
                <th scope="col" class="text-center">Modifier</th>
                <th scope="col" class="text-center">Supprimer</th>
            </tr>
        </thead>
        <tbody>
<?php 

$tableauCampings = $_SESSION["Personnel"]->getCamping(1)->setTableauCampings();


for ($i=0; $i < count($tableauCampings) ; $i++) { 
    echo '  <tr class="">
                <td scope="row" class="col-md-1 text-center">'.$tableauCampings[$i]->getIdCamping().'</td>
                <td scope="row" class="col-md-6 text-center">'.$tableauCampings[$i]->getNomCamping().'</td>
                <td scope="row" class="col-md-6 text-center">'.$tableauCampings[$i]->getActifCamping().'</td>
                
                <td scope="row" class="col-sm-1 text-center">
                        '.

                        $tableauCampings[$i]->BtnModalCamping("update","Modifier","btn-info",$tableauCampings[$i]->getIdCamping()).
                        $tableauCampings[$i]->ModalCamping("update","Modifier un role",$tableauCampings[$i]->getIdCamping(),$tableauCampings[$i]->getNomCamping())

                        .'
                </td>
                <td scope="row" class="col-sm-1 text-center">
                    '. 
                        
                    $tableauCampings[$i]->BtnModalCamping("delete","Suprimer","btn-danger",$tableauCampings[$i]->getIdCamping()).
                    $tableauCampings[$i]->ModalCamping("delete","Supprimer un role",$tableauCampings[$i]->getIdCamping(),$tableauCampings[$i]->getNomCamping())
                    .
                     '
                </td>
            </tr>';
}
?>

        </tbody>
    </table>
</div>

</div>

</body>

</html>