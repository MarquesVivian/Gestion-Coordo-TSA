<?php
session_start();
if(empty($_SESSION["autoriser"])){
  $_SESSION["autoriser"] = "non";
}

?>
<!DOCTYPE html>
<html>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="../index.php">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../personnels/">personnels</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../roles/">roles</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../activites.php">activites</a>
      </li>
      <?php
      if ($_SESSION["autoriser"] != "oui") {
        echo '
        <li class="nav-item">
          <a class="nav-link" href="../securites/login">Connexion</a>
        </li>';
      } else {
        echo '
        <li class="nav-item">
          <a class="nav-link" href="../securites/deconnexion">Deconnexion</a>
        </li>';
      }
      ?>
    </ul>
  </div>
</nav>
<?php
var_dump($_SESSION);
?>