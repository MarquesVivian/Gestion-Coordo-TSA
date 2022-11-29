<?php
include("../navBar.php");
@$login = $_POST["login"];
@$pass = md5($_POST["pass"]);
//@$pass = $_POST["pass"];
@$valider = $_POST["valider"];
$erreur = "";
var_dump(@$pass);
if (isset($valider)) {
   include("../DAO.php");
   $sel = $bdd->query('SELECT `personnels`.`id_P`, `nom_P`, `prenom_P`,`photo_P`, `num_Tel_P`, `email_P`, `personnels`.`id_R`, `libelle_R`, `active_R` from `personnels`
   RIGHT JOIN `roles` on `personnels`.`id_R` = `roles`.`id_R`
   where `email_P`="' . $login . '" AND `motDePasse`LIKE"' . $pass . '" AND `personnels`.`active_P` = 1 limit 1');

   $tab = $sel->fetchAll();
   if (count($tab) > 0) {


      switch ($tab[0]["id_R"]) {
         case '5':
            $insP = $bdd->query('SELECT * FROM `campings` WHERE `active_Cam` = true;');
            break;

         default:
            $insP = $bdd->query('SELECT * FROM `travaille` INNER JOIN `campings` on `travaille`.`id_Cam` = `campings`.`id_Cam` WHERE `id_P` = ' . $tab[0]["id_P"] . ' AND `active_Cam` = true ;');
            break;
      }

      $i = 0;
      $arrayCampings;
      
      while ($camPerso = $insP->fetch()) {



         $camp = new Camping($camPerso["id_Cam"],$camPerso["nom_Cam"],$camPerso["active_Cam"]);
         $arrayCampings[$i] = $camp;
         $i++;
      }
      $role = new Role($tab[0]["id_R"],$tab[0]["libelle_R"],$tab[0]["active_R"]);
      $_SESSION["Personnel"] = new Personnel($tab[0]["id_P"], $tab[0]["nom_P"], $tab[0]["prenom_P"], $tab[0]["photo_P"], $tab[0]["num_Tel_P"], $tab[0]["email_P"], $arrayCampings, $role);
      $_SESSION["connecter"] = "oui";
      header("location:../index.php");
   } else {

      $erreur = "Mauvais login ou mot de passe!";
   }
}


?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="utf-8" />
   <style>
      * {
         font-family: arial;
      }

      body {
         margin: 20px;
      }

      input {
         border: solid 1px #2222AA;
         margin-bottom: 10px;
         padding: 16px;
         outline: none;
         border-radius: 6px;
      }

      .erreur {
         color: #CC0000;
         margin-bottom: 10px;
      }

      a {
         font-size: 12pt;
         color: #EE6600;
         text-decoration: none;
         font-weight: normal;
      }

      a:hover {
         text-decoration: underline;
      }
   </style>
</head>

<body onLoad="document.fo.login.focus()">


<br>
<br>
<br>
<br>
      <div class="row">
         <div class="d-flex justify-content-center">
            <div class="erreur"><?php echo $erreur ?></div>
            <form name="fo" method="post" action="">
               <input type="text" name="login" placeholder="exemple@ex.ex" /><br />
               <input type="password" name="pass" placeholder="Mot de passe" /><br />
               </div>
      </div>
      <div class="row">
         <div class="d-flex justify-content-center">
               <input type="submit" name="valider" value="S'authentifier" />

            </form>
         </div>
      </div>



         
            




   
</body>

</html>