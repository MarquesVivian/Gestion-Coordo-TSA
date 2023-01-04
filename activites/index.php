
    <?php
    include("../navBar.php");

    if ($_SESSION["connecter"] != "oui") {
        header("Location: http://testcoordo/securites/login");
            exit();

    }else{
        $perso = $_SESSION["Personnel"];
    }
    
    ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activitées</title>
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

    if (in_array($_SESSION['Personnel']->getRole()->getIdRole(), array(4,5))) {//si c'est un coordo on lui met un choix de camping
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
                
                <?php
                if (in_array($_SESSION['Personnel']->getRole()->getLibelleRole(), array("Responsable d'Animation","Coordinateur", "Administration TSA"))) {
                    echo'<th scope="col" class="text-center">Active</th>
                    <th scope="col" class="text-center">Modifier</th>
                    <th scope="col" class="text-center">Supprimer</th>';
                }
                ?>

            </tr>
        </thead>
        <tbody>
<?php 

$tableauRole = $a->setTableauActivites($_SESSION["camp"]);
if (count($tableauRole) > 0) {
    for ($i=0; $i < count($tableauRole) ; $i++) { 
        echo '  <tr class="">
                    <td scope="row" class="col-md-1 text-center">'.$tableauRole[$i]->getIdActivite().'</td>
                    <td scope="row" class="col-md-2 text-center">'.$tableauRole[$i]->getLibelleActivite().'</td>
                    <td scope="row" class="col-md-6 text-center">'.$tableauRole[$i]->getDescriptionActivite().'</td>
                    <td scope="row" class="col-md-1 text-center" style="padding-bottom: unset;"> <input type="button" style="
                    width: 100%;
                    height: 100%;
                    padding: none;
                    background-color: '.$tableauRole[$i]->getCouleurActivite().';
                "></td>
                    <td scope="row" class="col-md-1 text-center">'.$tableauRole[$i]->getCampingActivite().'</td>';
                    if (in_array($_SESSION['Personnel']->getRole()->getLibelleRole(), array("Responsable d'Animation","Coordinateur", "Administration TSA"))) {
                        echo'
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
                </td>';
                    }
                    

                echo'</tr>';;
    }
}else{
    $i=0;
    $nomCamp = "";
    while ($i < count($_SESSION["SelectCamp"]) ) {
        if(array_search($_SESSION["camp"],$_SESSION["SelectCamp"][$i])!= false){
            $nomCamp = $_SESSION["SelectCamp"][$i]["nom_Cam"];
        }
        $i++;
    }
    
    echo'<tr class="">
        <td scope="row" colspan="8" class="col-md-1 text-center text-danger">Pas d\'activitées existantent pour le Camping : '.$nomCamp.'</td>
    </tr>';
}

?>

        </tbody>
    </table>
</div>

</div>

</body>

</html>