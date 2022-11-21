<?php
   session_start();
   @$login=$_POST["login"];
   @$pass=($_POST["pass"]);
   @$valider=$_POST["valider"];
   $erreur="";
   if(isset($valider)){
      //include("connexion.php");
      include ("../DAO.php");
      $sel=$bdd->query("select personnels.id_P,nom_P,prenom_P,personnels.id_R,libelle_R,campings.id_Cam,campings.nom_Cam from personnels
      RIGHT JOIN roles on personnels.id_R = roles.id_R
      INNER JOIN travaille on personnels.id_P = travaille.id_P
      INNER JOIN  campings on travaille.id_Cam = campings.id_Cam
      where email_P='".$login."' and motDePasse='".$pass."' AND personnels.active_P = 1 limit 1");
      $tab=$sel->fetchAll();
      var_dump($tab);
      if(count($tab)>0){
         $_SESSION["Personnel"]["prenom"]=ucfirst(strtolower($tab[0]["prenom_P"]));
         $_SESSION["Personnel"]["nom"]=ucfirst(strtolower($tab[0]["nom_P"]));
         $_SESSION["Personnel"]["nomrole"] = $tab[0]["libelle_R"];
         $_SESSION["Personnel"]["nomcamping"] = $tab[0]["nom_Cam"];
         $_SESSION["Personnel"]["idrole"] = $tab[0]["id_R"];
         $_SESSION["Personnel"]["idcamping"] = $tab[0]["id_Cam"];
         $_SESSION["Personnel"]["autoriser"]="oui";
         //header("location:session.php");
         header("location:../index.php");
      }
      else
         $erreur="Mauvais login ou mot de passe!";
   }
   var_dump($_SESSION);
   include("../navBar.html")
?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8" />
      <style>
         *{
            font-family:arial;
         }
         body{
            margin:20px;
         }
         input{
            border:solid 1px #2222AA;
            margin-bottom:10px;
            padding:16px;
            outline:none;
            border-radius:6px;
         }
         .erreur{
            color:#CC0000;
            margin-bottom:10px;
         }
         a{
            font-size:12pt;
            color:#EE6600;
            text-decoration:none;
            font-weight:normal;
         }
         a:hover{
            text-decoration:underline;
         }
      </style>
   </head>
   <body onLoad="document.fo.login.focus()">
      <h1>Authentification [ <a href="inscription.php">Créer un compte</a> ]</h1>
      <div class="erreur"><?php echo $erreur ?></div>
      <form name="fo" method="post" action="">
         <input type="text" name="login" placeholder="Login" /><br />
         <input type="password" name="pass" placeholder="Mot de passe" /><br />
         <input type="submit" name="valider" value="S'authentifier" />
      </form>
   </body>
</html>