<html>

<body>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../personnels/style.css" rel="stylesheet">



    <?php


    include("../navBar.php");

//Fonction qui vérifie si un chaine est bien une date sous format "YYYY-mm-dd"
    function isValid($date, $format = 'Y-m-d')
    {
        $dt = DateTime::createFromFormat($format, $date);
        return $dt && $dt->format($format) === $date;
    }
    


    

    $valide = false;
    //On vérifie si $_GET["j"] existe  et qu'il est bien une date
    if (!empty($_GET["j"]) && isValid($_GET["j"])) {
        $valide = true;
    } else {
        var_dump("Problemme de date");
        //header("Location: http://testcoordo/calendrier/index.php?j=".date('Y-m-d')."");
    }


    //si il n'y a pas d'érreur sur la date donner
    if ($valide) {


        //on créer une date grâce au parametre "j"
        $date = date_create($_GET["j"]);

        //on formate la date en version "YYYY-mm-dd"
        $dateFormater_Ymd = date_format($date, "Y-m-d");

        //on formate la en version "01 Janvier 2000"
        $dateFormater_dFY = date_format($date, "d F Y");

        //
        $dateM1 = date_add(date_create($dateFormater_Ymd), date_interval_create_from_date_string("-1 days"));
        $dateM1Formater_Ymd = date_format($dateM1, "Y-m-d");
        $dateP1 = date_add(date_create($dateFormater_Ymd), date_interval_create_from_date_string("1 days"));
        $dateP1Formater_Ymd = date_format($dateP1, "Y-m-d");


        include '../DAO.php'; // on inclue la class de base de donner
        //$date = date('d F Y');

        //on fait une requete pour avoir tout les personnels valide et qui appartienne au bon camping
        $insP = $bdd->query('SELECT id_P,nom_P,prenom_P
FROM `personnels`
WHERE `active_P` = 1 
ORDER BY personnels.id_P;');





        //le tableau et = au <table> qui sera afficher ($tableauPersonne[nbAnimateur][65])

        //la premiere case sera juste une case informative

        $tableauPersonne[0][0] = "    
        <div class='container-fluid'>
            <h1 class='d-flex justify-content-center'>
                <a href='http://testcoordo/calendrier/index.php?j=" . $dateM1Formater_Ymd . "'>
                    <
                </a>
                <span> 
                    Calendrier du " . $dateFormater_dFY . " 
                </span>
                <a href='http://testcoordo/calendrier/index.php?j=" . $dateP1Formater_Ymd . "'>
                    >
                </a> 
            </h1>
            <table class='table table-bordered table-hover  table-primary table-striped'>
            <thead>
                <tr>
                    <th style='padding-left: 0px; padding-right: 0px;' scope='col'>
                        <input type='submit' style='width : 100%;' value='Animateurs' >
                    </th>";

        $horraire = 8;
        $idemiH = 0;
        $ihorraire = 1;
        for ($i = 1; $i < 65; $i++) { //la premiere ligne sera composer d'une heure (de 8 à 24) suivi de 3 champs vide qui represent 15 30 et 45 minutes
            //test


            if ($i == $ihorraire) {
                $tableauPersonne[0][$i] = "<th scope='col' colspan='4' style='border-left: 2px solid black; text-align: center;' ><p> " . $horraire . "h</p></th>"; //les horraire de 8 a 24
                $horraire++;
                $ihorraire += 4;
                $idemiH = 0;
            } elseif ($idemiH == 3) {
                //$tableauPersonne[0][$i] = "<td style='border-left: 2px dashed gray;'></td>";
                $tableauPersonne[0][$i] = "";
                //$idemiH += 3;
            } else {
                //$tableauPersonne[0][$i] = "<th></th>"; //les case vide
                $tableauPersonne[0][$i] = ""; //les case vide
                //$idemiH++;
            }
            $idemiH++;
        }
        $tableauPersonne[0][64] .= "
                </tr>
            </thead>
            <tbody>";

        $lignePersonne = 1; //ligne du tableau après la ligne 0 qui représente l'exemple

        //se tableau vas contenir des numéro de case qui ston associer a des numéro de case contenant les horaire, il sera utiliser pour un rajouter une class dans les "<td>"
        $tableauH;
        for ($h = 0, $t = 1; $h <= 16; $h++, $t += 4) {
            $tableauH[$h] = $t;
        }

        while ($ligneP = $insP->fetch()) { //Pour chaque personne (a rajouter le camping et le active)
            $tableauPersonne[$lignePersonne][0] = "
        <tr>
            <th scope='row' style='padding-left: 0px; padding-right: 0px; vertical-align: middle;'>
                <a href='index.php' style='text-decoration: none;'><button style='display:block;width:100%;line-height:30px;color : red;'>" . $ligneP["nom_P"] . "</button></a>
            </th>";



            // on recherche toutes les activités du jours donnée de l'animateurs

            $requetetest = 'SELECT TIMESTAMPDIFF(MINUTE,TIMESTAMP(date("' . $dateFormater_Ymd . '"), \'8:00:00\'), dateDebut_An) as difPremier,
        dateDebut_An,
        dateFin_An,
        TIMESTAMPDIFF(MINUTE, dateDebut_An, dateFin_An) as dureeActivite,
        libelle_Act,
        couleur_Act
        FROM `personnels`
        LEFT JOIN animations ON personnels.id_P = animations.id_P 
        LEFT JOIN activites ON activites.id_Act = animations.id_Act
        WHERE personnels.id_P = ' . $ligneP["id_P"] . ' AND DATE(dateDebut_An) LIKE "' . $dateFormater_Ymd . '"
        ORDER BY dateDebut_An;';
            $insA = $bdd->query($requetetest);

            $case = 1;
            $dif = 0;
            $hMoins1 = 0;
            //on vas remplir le tableau
            while ($animation = $insA->fetch()) { //pour toute les animations d'un animateurs

                $dateDebut = explode(" ", $animation["dateDebut_An"]);
                $horaireDebut = explode(":", $dateDebut[1]);

                $dateFin = explode(" ", $animation["dateFin_An"]);
                $horaireFin = explode(":", $dateFin[1]);
                $dureeActivite = ((int) $animation["dureeActivite"] / 15); //l'acitivité qui est donner en Minutes et diviser pour avoir le nombre de quarts d'heure

                $dureeActivite = $dureeActivite > ((int)$dureeActivite) ? ((int)$dureeActivite + 1) : $dureeActivite; //si $a est superieur a (int)$a ca veut dire que c'est un float donc on le fait passer en INT+1 pour avoir la bonne durée

                $dif = (int) $animation['difPremier'] / 15 + 1;
                $quart = 0;

                for ($case; $case < (int)$dif; $case++) { //on remplis de rien tant qu'on a pas atteind le début de l'activité

                    //si le numéro de la case existe dans le tableau des horaire
                    if (in_array($case, $tableauH)) {
                        $tableauPersonne[$lignePersonne][$case] = "<td style='border-left: 2px solid black;'></td>";

                        $quart = 0;
                    } elseif ($quart == 2) {
                        //si l'heure d'avant est entre %h15 et %h30 on décale de -1 la case dashed (c'est pour éviter un décalement chiant)
                        if ($hMoins1 >= 15 && $hMoins1 < 30) {
                            $tableauPersonne[$lignePersonne][$case - 1] = "<td style='border-left: 2px dashed gray;'></td>";
                            $tableauPersonne[$lignePersonne][$case] = "<td></td>";
                            $hMoins1 = 0;
                        } else {
                            $tableauPersonne[$lignePersonne][$case] = "<td style='border-left: 2px dashed gray;'></td>";
                        }

                        $quart = 0;
                    } else {
                        $tableauPersonne[$lignePersonne][$case] = "<td></td>";
                    }
                    $quart++;
                }
                //on initialise hmoins1 a la date de fin pour faire un if un peu plus haut
                $hMoins1 = $horaireFin[1];

                //ici on est arriver a udébut de l'acitivité
                //on lui donne la taille qu'elle dois avoir en colspan

                $tableauPersonne[$lignePersonne][$case] = "
                <td style='background : " . $animation["couleur_Ac"] . " ;box-shadow: none; ' colspan='" . ((int)$dureeActivite) . "'>
                    <span class='texte-hover texte-original'>" . $animation["libelle_Ac"] . "<br> " . $horaireDebut[0] . "H" . $horaireDebut[1] . " " . $horaireFin[0] . "H" . $horaireFin[1] . "</span>
                </td>";
                //on remplis les case dudébut de lactivté jusqu'a la fin de l'activité par rien
                $case++;
                $test = $case + (int)$dureeActivite;
                for ($case; $case < $test - 1; $case++) {
                    $tableauPersonne[$lignePersonne][$case] = "";
                }
            }
            $quart = 0;

            //si il n'y a plus aucun activité on fini de remplir la ligne
            for ($case; $case < 65; $case++) {
                if (in_array($case, $tableauH)) {
                    $tableauPersonne[$lignePersonne][$case] = "<td style='border-left: 2px solid black;'></td>";
                    $quart = 0;
                } elseif ($quart == 2) {
                    $tableauPersonne[$lignePersonne][$case] = "<td style='border-left: 2px dashed gray;'></td>";
                    $quart = 0;
                } else {
                    $tableauPersonne[$lignePersonne][$case] = "<td></td>";
                }
                $quart++;
            }
            $tableauPersonne[$lignePersonne][$case - 1] .= "</tr>";
            $lignePersonne++;
        }
        $tableauPersonne[sizeof($tableauPersonne) - 1][sizeof($tableauPersonne[0]) - 1] .= " 
            </tbody>
        </table>
    </div>";

        //-----------------------------------------------------------Création du tableau-----------------------------------------------------------


        for ($t = 0; $t < sizeof($tableauPersonne); $t++) {
            for ($y = 0; $y < sizeof($tableauPersonne[$t]); $y++) {
                echo $tableauPersonne[$t][$y];
            }
        }
    }
    ?>




</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>