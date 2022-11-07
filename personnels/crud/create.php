<html>

   
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