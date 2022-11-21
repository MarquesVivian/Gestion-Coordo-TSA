<html>
    <?php 
    include("../../navBar.php")
    ?>
    <table style="border: 1px solid #333;">
    <tr>
                    <td style='border: 1px solid #333;'>id</td>
                    <td style='border: 1px solid #333;'>Nom</td>
                    <td style='border: 1px solid #333;'>Prenom</td>
                    <td style='border: 1px solid #333;'>Numéro</td>
                    <td style='border: 1px solid #333;'>Email</td>
                    <td style='border: 1px solid #333;'>nom Camping</td>
                    <td style='border: 1px solid #333;'>nom Role</td>

                </tr>
 <?php
 include ("../../DAO.php");
$ins=$bdd->query('SELECT id_P,nom_P,prenom_P,num_Tel_P,email_P,campings.nom_Cam as nomCam,roles.libelle_R as nomR 
FROM `personnels` 
INNER JOIN campings ON personnels.fk_Cam_P = campings.id_Cam 
INNER JOIN roles on personnels.fk_R_P = roles.id_R 
ORDER BY `id_P`;');
while ($donnee=$ins->fetch( )) {
            echo"
            
                <tr>
                    <td style='border: 1px solid #333;'>", $donnee["id_P"], "</td>
                    <td style='border: 1px solid #333;'>", $donnee["nom_P"], "</td>
                    <td style='border: 1px solid #333;'>", $donnee["prenom_P"], "</td>
                    <td style='border: 1px solid #333;'>", $donnee["num_Tel_P"], "</td>
                    <td style='border: 1px solid #333;'>", $donnee["email_P"], "</td>
                    <td style='border: 1px solid #333;'>", $donnee["nomCam"], "</td>
                    <td style='border: 1px solid #333;'>", $donnee["nomR"], "</td>

                </tr><br>";
        }
?>
</table>
    <body>
        <form  action="envoyebdd.php" methode="post">
        <input type="hidden" name="table" value="personnels"/><br>
            Nom : <input type="text" name="nom" placeholder="Nom"/><br>
            Prenom : <input type="text" name="prenom" placeholder="Prenom"><br>
            tel : <input type="text" name="tel" maxlenght ="12"><br>
            mail : <input type="text" name="mail" maxlenght ="12"><br>
            mdr : <input type="text" name="mdp"><br>
            <label for="campings-select">Choisir un camping</label>
            <select name="campings" id="camping-select">
            <?php

            $ins =$bdd->query('SELECT * FROM campings;');
            while ($donnee=$ins->fetch( )) {
            echo"<option value='".$donnee["id_Cam"]."'>".$donnee["nom_Cam"]."</option>";
        }


?>
            </select>
            <br>
            <label for="role-select">Choisir un rôle:</label>
            <select name="roles"id="roles-select">
            <?php

$ins =$bdd->query('SELECT * FROM roles;');
    while ($donnee=$ins->fetch( )) {
            echo"<option value='".$donnee["id_R"]."'>".$donnee["libelle_R"]."</option>";
        }


?>
            </select>
        </input>
        <input type="submit" value="Envoyer">
        </form>
    </body>
</html>
<?php

echo date('Y-m-d h:i:s');

?>