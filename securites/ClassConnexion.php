<?php
class Connexion{
    protected $identifiant;

    protected $hasheMotDePasse;

    protected $token;

    protected $erreur;


    private function __construct()

    {
        $this->token = true;

    }



    //fonction qui retourne un tableau de personnel (TOUS)
    public function VerifId($identifiant){
    include "../DAO.php";
    $insPersonnels = $bdd->query('SELECT `id_P`,`nom_P`,`prenom_P`,`num_Tel_P`,`email_P`,`photo`,`fk_Cam_P`,`fk_R_P` 
    FROM `personnels` 
    WHERE `active` = 1 
    ORDER BY personnels.id_P;');

    
}
    

}








/*
SELECT personnels.id_P, nom_P, prenom_P, num_Tel_P, email_P, photo_P, id_R, libelle_R, campings.id_Cam, nom_Cam, personnels.active_P
from personnels
INNER JOIN roles on roles.id_R = personnels.fk_R_P
INNER JOIN travaille on travaille.id_P = personnels.id_P
INNER JOIN campings ON campings.id_Cam = travaille.id_Cam
WHERE personnels.active_P = 1 AND campings.active_Cam = 1


*/
?>