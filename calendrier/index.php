<html>

<body>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../personnels/style.css" rel="stylesheet">



    <?php
    include("../navBar.html");
    session_start();
    include '../DAO.php'; // on inclue la class de base de donner
    $date = date('d F Y');

    //on fait une requete pour avoir tout les personnels valide et qui appartienne au bon camping
    $insP = $bdd->query('SELECT id_P,nom_P,prenom_P
FROM `personnels`
ORDER BY personnels.id_P;');

    //le tableau et = au <table> qui sera afficher ($tableauPersonne[nbAnimateur][65])


    //la premiere case sera juste une case informative
    $tableauPersonne[0][0] = "    
        <div class='container-fluid'>
            <h1 class='d-flex justify-content-center'>Calendrier du " . $date . " </h1>
            <table class='table table-bordered table-dark'>
            <thead>
                <tr>
                    <th style='padding-left: 0px; padding-right: 0px;'>
                        <input type='submit' style='width : 100%; background-color: blueviolet;' value='Animateurs' >
                    </th>";

    $horraire = 8;
    $i4 = 1;
    for ($i = 1; $i < 65; $i++) { //la premiere ligne sera composer d'une heure (de 8 à 24) suivi de 3 champs vide qui represent 15 30 et 45 minutes


        if ($i == $i4) {
            $tableauPersonne[0][$i] = "<th>" . $horraire . "</th>"; //les horraire de 8 a 24
            $horraire++;
            $i4 += 4;
        } else {
            $tableauPersonne[0][$i] = "<th></th>"; //les case vide
        }
    }
    $tableauPersonne[0][64] .= "
                </tr>
            </thead>
            <tbody>";

    $lignePersonne = 1; //ligne du tableau après la ligne 0 qui représente l'exemple

    //se tableau vas contenir des numéro de case, il sera utiliser pour un rajouter une class dans les "<td>" qui sont associer à des quart d'heure
    $tableauH;
    for ($h = 0, $t = 1; $h <= 16; $h++, $t += 4) {
        $tableauH[$h] = $t;
    }

    while ($ligneP = $insP->fetch()) { //Pour chaque personne (a rajouter le camping et le active)
        $tableauPersonne[$lignePersonne][0] = "
        <tr>
            <td style='padding-left: 0px; padding-right: 0px;'>
                <input type='submit' style='width : 100%; background-color: blueviolet;' value=" . $ligneP["nom_P"] . " >
            </td>";



        //on vas remplir le tableau
        $insA = $bdd->query('SELECT TIMESTAMPDIFF(MINUTE,TIMESTAMP(CURDATE(), \'8:00:00\'), dateDebut_An) as difPremier,
        dateDebut_An,
        dateFin_An,
        TIMESTAMPDIFF(MINUTE, dateDebut_An, dateFin_An) as dureeActivite,
        libelle_Ac,
        couleur_Ac,
        CURDATE()
        FROM `personnels`
        LEFT JOIN animations ON personnels.id_P = animations.fk_P_An 
        LEFT JOIN activites ON activites.id_Ac = animations.fk_Ac_An
        WHERE fk_P_An = ' . $ligneP["id_P"] . ' AND DATE(dateDebut_An) like CURDATE()
        ORDER BY dateDebut_An;');

        $case = 1;
        $dif = 0;

        while ($animation = $insA->fetch()) { //pour toute les animations d'un animateurs

            $dateDebut = explode(" ", $animation["dateDebut_An"]);
            $horaireDebut = explode(":", $dateDebut[1]);

            $dateFin = explode(" ", $animation["dateFin_An"]);
            $horaireFin = explode(":", $dateFin[1]);
            $dureeActivite = ((int) $animation["dureeActivite"] / 15); //l'acitivité qui est donner en Minutes et diviser pour avoir le nombre de quarts d'heure

            $dureeActivite = $dureeActivite > ((int)$dureeActivite) ? ((int)$dureeActivite + 1) : $dureeActivite; //si $a est superieur a (int)$a ca veut dire que c'est un float donc on le fait passer en INT+1 pour avoir la bonne durée

            $dif = (int) $animation['difPremier'] / 15 + 1;
            for ($case; $case < (int)$dif; $case++) { //on remplis de rien tant qu'on a pas atteind le début de l'activité
                $tableauPersonne[$lignePersonne][$case] = in_array($case, $tableauH) ? "<td class='horraire'></td>" : "<td class='horraire quart'></td>";
            }

            //ici on est arriver a udébut de l'acitivité
            //on lui donne la taille qu'elle dois avoir
            //je met un hover pour avoir plus de detail quand on passe la souris dessus

            $tableauPersonne[$lignePersonne][$case] = "
            
                <td class='horraire' style='background : " . $animation["couleur_Ac"] . "' colspan='" . ((int)$dureeActivite) . "'>
                    <span class='texte-hover texte-original'>" . $animation["libelle_Ac"] . " " . $horaireDebut[0] . "H". $horaireDebut[1] . " " . $horaireFin[0] . "H". $horaireFin[1] ."</span>

                </td>
            ";

            //on remplis les case après le début pendant tout la duré de rien
            $case++;
            $test = $case + (int)$dureeActivite;
            for ($case; $case < $test - 1; $case++) {
                $tableauPersonne[$lignePersonne][$case] = "";
            }
        }

        for ($case; $case < 65; $case++) {
            $tableauPersonne[$lignePersonne][$case] = in_array($case, $tableauH) ? "<td class='horraire'></td>" : "<td class='horraire quart'></td>";
        }
        $tableauPersonne[$lignePersonne][$case - 1] .= "
                    </tr>";
        $lignePersonne++;
    }
    $tableauPersonne[sizeof($tableauPersonne) - 1][sizeof($tableauPersonne[0]) - 1] .= " 
    </tbody>
</table>";

    //-----------------------------------------------------------Création du tableau-----------------------------------------------------------


    for ($t = 0; $t < sizeof($tableauPersonne); $t++) {
        for ($y = 0; $y < sizeof($tableauPersonne[$t]); $y++) {
            echo $tableauPersonne[$t][$y];
        }
    }



    ?>



</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>