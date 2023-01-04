<?php
include("personnels/personnels.php");
include("roles/roles.php");
include("campings/campings.php");
include("activites/activites.php");
include("animations/animations.php");
session_start();
if(empty($_SESSION["connecter"])){
  $_SESSION["connecter"] = "non";
}


if(!empty($_SESSION['timestamp'])){
  if(time() - $_SESSION['timestamp'] > 21600) { //si c'est au dessus de 6h d'inactivité
    header("Location:../securites/deconnexion.php"); //redirect to dexonnexion
    exit;
  } else {
    $_SESSION['timestamp'] = time(); //set new timestamp
  }
}



?>
<!DOCTYPE html>
<html>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
    <?php
        if ($_SESSION["connecter"] == "oui") {
        $i = 1;
        switch ($i) {
          case ($_SESSION["Personnel"]->getRole()->getIdRole() >= 5):
            echo '
            <li class="nav-item">
              <a class="nav-link" href="../roles/">roles</a>
            </li>';
          case ($_SESSION["Personnel"]->getRole()->getIdRole() > 4):
            echo '
            
            <li class="nav-item">
            <a class="nav-link" href="../campings/">Campings</a>
          </li>';
          case ($_SESSION["Personnel"]->getRole()->getIdRole() >= 1):
            echo'      <li class="nav-item active">
            <a class="nav-link" href="../index.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../personnels/">personnels</a>
              </li>

          
          <li class="nav-item">
            <a class="nav-link" href="../activites">activites</a>
          </li>';


            break;
        }
        echo '
        <li class="nav-item">
          <a class="nav-link" href="../securites/deconnexion">Deconnexion</a>
        </li>';
        $perso = $_SESSION["Personnel"];
        $nomPersonnel = $perso->getNom();
        echo '<li class="nav-item">'.$nomPersonnel. '</li>';
      }else {
        echo '
        <li class="nav-item">
          <a class="nav-link" href="../securites/login">Connexion</a>
        </li>';
      }
      ?>
    </ul>
  </div>
</nav>

<?php
// var_dump($_SESSION);
?>