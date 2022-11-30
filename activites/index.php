
    <?php
    include("../navBar.php");

    if ($_SESSION["connecter"] != "oui") {
        header("Location: http://testcoordo/securites/login");
            exit();

    }else{
        $perso = $_SESSION["Personnel"];
        if(!in_array($_SESSION['Personnel']->getRole()->getIdRole(),array(2,4,5))){
            header("Location: http://testcoordo/securites/login");
            exit();
        }
    }
    
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
        $a = new Activite("a","a","a","a","a","a"); 
    echo $a->BtnModalActivite("create","Création d'une Activite","btn-primary col-lg-5","id");
    echo $a->ModalActivite("create","Création d'une Activite","id","","","","","bg-primary","");

    if (!empty($_POST["campings"])) {
        $_SESSION["camp"] = $_POST["campings"];
    }

    if (in_array($_SESSION['Personnel']->getRole()->getIdRole(), array(4))) {//si c'est un coordo on lui met un choix de camping
        echo '<br><br>
        <form action="" method="post">
        <div class="row">
            <div class="col text-center">
                <label for="campings-select">Camping </label> ';

                   echo $perso->ChoixCampingsCreationPerso();

        echo'
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </div>
        </div>
        </form>
        <br><br>';
        echo "<div class='row justify-content-md-center '>
            <div class='text-center col-lg-5 border border-3 border-primary'><h4>Vous etes Actuellement sur le camping : <br>".  $perso->getNomCampingChoisi($_SESSION["camp"])."</h4></div>
            </div>";
    }

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

$tableauRole = $a->setTableauActivites($_SESSION["camp"]);
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
                        $tableauRole[0]->ModalActivite("update","Modifier une Activité",$tableauRole[$i]->getIdActivite(),$tableauRole[$i]->getLibelleActivite(),$tableauRole[$i]->getDescriptionActivite(),$tableauRole[$i]->getCouleurActivite(),$tableauRole[$i]->getCampingActivite(),"bg-success","")

                        .'
                </td>
                <td scope="row" class="col-sm-1 text-center">
                    '. 
                    $tableauRole[0]->BtnModalActivite("delete","Suprimer","btn-danger",$tableauRole[$i]->getIdActivite()).
                    $tableauRole[0]->ModalActivite("delete","Supprimer une Activité",$tableauRole[$i]->getIdActivite(),$tableauRole[$i]->getLibelleActivite(),$tableauRole[$i]->getDescriptionActivite(),$tableauRole[$i]->getCouleurActivite(),$tableauRole[$i]->getCampingActivite(),"bg-danger","readonly")
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