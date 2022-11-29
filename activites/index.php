
    <?php
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
    $a = new Activite("a","a","a","a","a","a"); 
    //var_dump($a->setTableauActivites());
    ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles</title>
</head>

<body>
    <div class="container-fluid">
        <?php
    echo $a->BtnModalActivite("create","Création d'une Activite","btn-primary col-lg-5","id");
    echo $a->ModalActivite("create","Création d'une Activite","id","","","","","bg-primary");

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
                <th scope="col" class="text-center">Description</th>
                <th scope="col" class="text-center">Couleur</th>
                <th scope="col" class="text-center">Camping</th>
                <th scope="col" class="text-center">Active</th>
                <th scope="col" class="text-center">Modifier</th>
                <th scope="col" class="text-center">Supprimer</th>
            </tr>
        </thead>
        <tbody>
<?php 

$tableauRole = $a->setTableauActivites();

for ($i=0; $i < count($tableauRole) ; $i++) { 
    echo '  <tr class="">
                <td scope="row" class="col-md-1 text-center">'.$tableauRole[$i]->getIdActivite().'</td>
                <td scope="row" class="col-md-2 text-center">'.$tableauRole[$i]->getLibelleActivite().'</td>
                <td scope="row" class="col-md-6 text-center">'.$tableauRole[$i]->getDescriptionActivite().'</td>
                <td scope="row" class="col-md-1 text-center">'.$tableauRole[$i]->getCouleurActivite().'</td>
                <td scope="row" class="col-md-1 text-center">'.$tableauRole[$i]->getCampingActivite().'</td>
                <td scope="row" class="col-md-1 text-center">'.$tableauRole[$i]->getActifActivite().'</td>
                
                <td scope="row" class="col-sm-1 text-center">
                        '.
                        $tableauRole[0]->BtnModalActivite("update","Modifier","btn-info",$tableauRole[$i]->getIdActivite()).
                        $tableauRole[0]->ModalActivite("update","Modifier une Activité",$tableauRole[$i]->getIdActivite(),$tableauRole[$i]->getLibelleActivite(),$tableauRole[$i]->getDescriptionActivite(),$tableauRole[$i]->getCouleurActivite(),$tableauRole[$i]->getCampingActivite(),"bg-success")

                        .'
                </td>
                <td scope="row" class="col-sm-1 text-center">
                    '. 
                    $tableauRole[0]->BtnModalActivite("delete","Suprimer","btn-danger",$tableauRole[$i]->getIdActivite()).
                    $tableauRole[0]->ModalActivite("delete","Supprimer une Activité",$tableauRole[$i]->getIdActivite(),$tableauRole[$i]->getLibelleActivite(),$tableauRole[$i]->getDescriptionActivite(),$tableauRole[$i]->getCouleurActivite(),$tableauRole[$i]->getCampingActivite(),"bg-danger")
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