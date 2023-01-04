
    <?php
    include("../navBar.php");

    if ($_SESSION["connecter"] != "oui") {
        header("Location: http://testcoordo/securites/login");
            exit();

    }else{
        $perso = $_SESSION["Personnel"];
        if(!in_array($_SESSION['Personnel']->getRole()->getLibelleRole(), array("Coordinateur", "Administration TSA"))){
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
    <title>Roles</title>
</head>

<body>
    <div class="container-fluid">
        <?php
    echo $_SESSION["Personnel"]->getRole()->BtnModalRole("create","Création d'un Role","btn-primary col-lg-5","");
    echo $_SESSION["Personnel"]->getRole()->ModalRole("create","Création d'un Role","","");

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
                <?php 
                if (in_array($_SESSION['Personnel']->getRole()->getLibelleRole(), array("Coordinateur", "Administration TSA"))) {
                    echo'
                    <th scope="col" class="text-center">Modifier</th>
                    <th scope="col" class="text-center">Supprimer</th>';
                }
                ?>

            </tr>
        </thead>
        <tbody>
<?php 

$tableauRole = $_SESSION["Personnel"]->getRole()->setTableauRole();

for ($i=0; $i < count($tableauRole) ; $i++) { 
    echo '  <tr class="">
                <td scope="row" class="col-md-1 text-center">'.$tableauRole[$i]->getIdRole().'</td>
                <td scope="row" class="col-md-6 text-center">'.$tableauRole[$i]->getLibelleRole().'</td>
                <td scope="row" class="col-md-6 text-center">'.$tableauRole[$i]->getActifRole().'</td>
                
                ';

                if (in_array($_SESSION['Personnel']->getRole()->getLibelleRole(), array("Coordinateur", "Administration TSA"))) {

                echo'<td scope="row" class="col-sm-1 text-center">
                        '.

                        $_SESSION["Personnel"]->getRole()->BtnModalRole("update","Modifier","btn-info",$tableauRole[$i]->getIdRole()).
                        $_SESSION["Personnel"]->getRole()->ModalRole("update","Modifier un role",$tableauRole[$i]->getIdRole(),$tableauRole[$i]->getLibelleRole())

                        .'
                </td>
                <td scope="row" class="col-sm-1 text-center">
                    '. 
                        
                        $_SESSION["Personnel"]->getRole()->BtnModalRole("delete","Suprimer","btn-danger",$tableauRole[$i]->getIdRole()).
                        $_SESSION["Personnel"]->getRole()->ModalRole("delete","Supprimer un role",$tableauRole[$i]->getIdRole(),$tableauRole[$i]->getLibelleRole())
                    .
                     '
                </td>';
            }
            echo'</tr>';
}
?>

        </tbody>
    </table>
</div>

</div>

</body>

</html>