<?php
class Personnel
{
    protected $id;

    protected $nom;

    protected $prenom;

    protected $numTel;

    protected $email;

    protected $Campings;

    protected $photo;

    protected $role;


    public function __construct($id, $nom, $prenom, $photo, $numTel, $email, $arrayCampings, $role)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->photo = $photo;
        $this->numTel = $numTel;
        $this->email = $email;
        $this->Campings = $arrayCampings;
        $this->role = $role;
    }

    #region GetteurSetteur
    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of nom
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @return  self
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of prenom
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set the value of prenom
     *
     * @return  self
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get the value of numTel
     */
    public function getNumTel()
    {
        return $this->numTel;
    }


        /**
     * Get the value of numTel
     */
    public function getNumTelModal($tel)
    {

        if ($tel != null && $tel != "") {
            $new_tel="";
            for ($i = 0; $i < strlen($tel); $i += 2) {
                $new_tel .= " " . substr($tel, $i, 2);
            }
        } else {
            $new_tel = "N° tel";
        }
        return $new_tel;
    }

    /**
     * Set the value of numTel
     *
     * @return  self
     */
    public function setNumTel($numTel)
    {
        $this->numTel = $numTel;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of Campings
     */
    public function getAllCampings()
    {
        return $this->Campings;
    }

    public function getCamping($i)
    {
        return $this->Campings[$i];
    }

    public function getCountCampings()
    {
        return count($this->Campings);
    }


    /**
     * Set the value of Campings
     *
     * @return  self
     */



    public function getRole()
    {
        return $this->role;
    }


    #endregion

    /**
     * Set the value of cardsPersonnels
     *
     * @return  self
     */
    public function setCardsPersonnels($insPersonnels)
    {
        require("../DAO.php");

        $iterator = 0;
        $row = 4;
        $card = "AUCUN PERSONNEL";
        while ($p = $insPersonnels->fetch()) {

            //si il n'y a pas d'image
            if ($p["photo_P"] == '' || !file_exists($p["photo_P"])) {
                $p["photo_P"] = "img/profile-user.png";
            }

            $insP = $bdd->query('SELECT * FROM `travaille` INNER JOIN `campings` on `travaille`.`id_Cam` = `campings`.`id_Cam` WHERE `id_P` = ' . $p["id_P"] . ';');

            $i = 0;
            $arrayCampings;
            while ($camPerso = $insP->fetch()) {
                $arrayCampings["id_Cam"][$i] = $camPerso["id_Cam"];
                $arrayCampings["nom_Cam"][$i] = $camPerso["nom_Cam"];
                $i++;
            }

            if ($p["num_Tel_P"] == null || $p["num_Tel_P"] == "") {
                $new_tel = "N° tel";
            }

            $role = new Role($p["id_R"], $p["libelle_R"], 1);

            $personnel = new Personnel($p["id_P"], $p["nom_P"], $p["prenom_P"], $p["photo_P"], $p["num_Tel_P"], $p["email_P"], $arrayCampings, $role);


            if ($row == 3) {
                $card .= "</div>
                    <div class='row' style='margin: 20px 20px 20px 20px;'>";

                $row = -1;
            } elseif ($row == 4) { //premier passage
                $card = "
                <div class='row' style='margin: 20px 20px 20px 20px;'>";
                $row = -1;
            }


            $bgImgCard = "";
            if ($personnel->role->getLibelleRole() == "Animateur") {
                $bgImgCard = "text-dark bg-info";
            } else if ($personnel->role->getLibelleRole() == "Responsable d'Animation") {
                $bgImgCard = "text-white bg-danger";
            } else if ($personnel->role->getLibelleRole() == "Administration Camping") {
                $bgImgCard = "";
            } else if ($personnel->role->getLibelleRole() == "Coordinateur") {
                $bgImgCard = "text-white bg-success";
            } else if ($personnel->role->getLibelleRole() == "Administration TSA") {
                $bgImgCard = "text-white bg-dark";
            }


            $card .= "<div class='col'>
                        
                            <div class='card arrondi bg-image hover-overlay' style='width: 18rem; '>
                            <form enctype='multipart/form-data' action='read.php?j=".date('Y-m-d')."' method='post'>
                                    <input type='hidden' name='table' value='personnels'/>
                                    <input type='hidden' name='id_P' value='".$personnel->getId()."'/>

                                    <button type='submit' class='btn' style='position: absolute;width: 100%;height: 51%;opacity: 0%;'></button>

                                    <div class='" . $bgImgCard . " arrondi-top'>
                                        <h5 class='text-center'>" . $personnel->role->getLibelleRole() . "  </h5>
                                        <img src='" . $p["photo_P"] . "' class='card-img-top text-dark bg-light img-card-perso ' alt='...'>
                                    </div>
                                    
                                </form>
                                    <div class='card-body text-center'>

                                        <h5 class='card-title border border-primary'>" . $personnel->getNom() . "</h5>
                                        <h5 class='card-title border border-primary'>" . $personnel->getPrenom() . "</h5>
                                        <h5 class='card-title border border-primary'>" . str_replace("@", "<br>@", $personnel->getEmail())  . "</h5>
                                        <h5 class='card-title border border-primary'>" . $personnel->getNumTelModal($personnel->getNumTel()) . "</h5>
                                        ";
                                        

            if (in_array($_SESSION['Personnel']->getRole()->getLibelleRole(), array("Responsable d'Animation","Coordinateur", "Administration TSA"))) {
                /**Modif */
                $card .= $this->BtnModalPerso("update","Modif","btn-info col-lg-5",$personnel->getId());
                $card .=$this->ModalPerso("update","Modification d'un Personnel",$personnel->getId(),$personnel->getNom(),$personnel->getPrenom(),$personnel->getNumTel(),$personnel->getEmail(),$personnel->getAllCampings()['id_Cam'][0],$personnel->role->getLibelleRole());
                /**Suppression */
                $card .= $this->BtnModalPerso("delete","Supp","btn-danger col-lg-5",$personnel->getId());
                $card .=$this->ModalPerso("delete","Suppression d'un Personnel",$personnel->getId(),$personnel->getNom(),$personnel->getPrenom(),$personnel->getNumTel(),$personnel->getEmail(),$personnel->getAllCampings()['id_Cam'][0],$personnel->role->getLibelleRole());
            }
            $card .= "
                                </div>
                            </div>
                        
                            <div></div></div>";

            $iterator++;
            $row++;
        }
        $card .= '</div>';
        echo $card;
    }




    //fonction qui retourne un tableau de personnel (TOUS)
    public function toutPersonnels()
    {
        include "../DAO.php";
        $insPersonnels = $bdd->query('SELECT `personnels`.`id_P`, `nom_P`, `prenom_P`, `photo_P`, `num_Tel_P`, `email_P`, `personnels`.`id_R`, `libelle_R`,`active_R` from `personnels`
        RIGHT JOIN `roles` on `personnels`.`id_R` = `roles`.`id_R`
        WHERE `personnels`.`active_P` = 1
        GROUP BY `personnels`.`id_P`
        ORDER BY `personnels`.`id_R` DESC;');

        $this->setCardsPersonnels($insPersonnels);
    }


    //fonction qui retourne un tableau de personnel en fonction du camping du RA
    public function toutPersonnelsCamping($idCamp)
    {
        include "../DAO.php";
        $insPersonnels = $bdd->query('SELECT `personnels`.`id_P`, `nom_P`, `prenom_P`, `photo_P`, `num_Tel_P`, `email_P`, `personnels`.`id_R`, `libelle_R` from `personnels`
        RIGHT JOIN `roles` on `personnels`.`id_R` = `roles`.`id_R`
        INNER JOIN `travaille` ON `personnels`.`id_P` = `travaille`.`id_P`
        INNER JOIN `Campings` ON `travaille`.`id_Cam` = `Campings`.`id_Cam`
        WHERE `personnels`.`active_P` = 1 
        AND `Campings`.`id_Cam` IN (' . $idCamp . ') 
        AND `active_tr` = 1
        GROUP BY `personnels`.`id_P`
        ORDER BY `personnels`.`id_R` DESC ;');

        $this->setCardsPersonnels($insPersonnels);
    }

    public function unPersonnels($idPerso){
        include "../DAO.php";
        $insPersonnels = $bdd->query('SELECT `personnels`.`id_P`, `nom_P`, `prenom_P`, `photo_P`, `num_Tel_P`, `email_P`, `personnels`.`id_R`, `libelle_R` from `personnels`
        RIGHT JOIN `roles` on `personnels`.`id_R` = `roles`.`id_R`
        INNER JOIN `travaille` ON `personnels`.`id_P` = `travaille`.`id_P`
        INNER JOIN `Campings` ON `travaille`.`id_Cam` = `Campings`.`id_Cam`
        WHERE `personnels`.`id_P` = '.$idPerso.' 
        GROUP BY `personnels`.`id_P`
        ORDER BY `personnels`.`id_R` DESC ;');

        $this->setCardsPersonnels($insPersonnels);
    }



    public function ChoixCampingsCreationPerso($selectC)
    {
        include "../DAO.php";
        $value = '
        <select name="campings" id="camping-select">';

        $ins = $bdd->query('SELECT `id_Cam`, `nom_Cam` FROM `campings` WHERE `id_Cam` IN (' . $this->ValueCampings() . ') ;');
        $i = 0;
        while ($donnee = $ins->fetch()) {
            if($donnee["id_Cam"] != $selectC){
            $value .= "<option value='" . $donnee["id_Cam"] . "'>" . $donnee["nom_Cam"] . "</option>";
            }else{
                $value .= "<option value='" . $donnee["id_Cam"] . "' selected='selected'>" . $donnee["nom_Cam"] . "</option>";
            }
            $_SESSION["SelectCamp"][$i]["id_Cam"] = $donnee["id_Cam"];
            $_SESSION["SelectCamp"][$i]["nom_Cam"] = $donnee["nom_Cam"];
            $i++;
        }
        $value .= "
        </select>
        ";

        return $value;
    }

    public function ValueCampings()
    {
        $cam = 1;

        $value = $_SESSION["Personnel"]->getCamping(0)->getIdCamping();
        while ($cam < count($_SESSION["Personnel"]->getAllCampings())) {
            $value .= ', ' . $_SESSION["Personnel"]->getCamping($cam)->getIdCamping();
            $cam++;
        }
        return $value;
    }



    private function ChoixRolesCreationPerso($selectR)
    {
        include "../DAO.php";
        $value = '
                <select name="roles"id="roles-select">';

        $ins = $bdd->query('SELECT * FROM roles WHERE id_R IN (' . $this->ValueRoles() . ');');
        
        while ($donnee = $ins->fetch()) {
            if($selectR == $donnee["libelle_R"]){
                $value .= "<option value='" . $donnee["id_R"] . "' selected='selected'>" . $donnee["libelle_R"] . "</option>";
            }else{
                $value .= "<option value='" . $donnee["id_R"] . "'>" . $donnee["libelle_R"] . "</option>";
            }
            
            
        }

        $value .= '</select>
        </input>

        ';

        return $value;
    }

    public function ValueRoles()
    {
        $value = "";

        switch ($_SESSION["Personnel"]->getRole()->getIdRole()) {
            case 2:
                $value = "1";
                break;
            case 4:
                $value = "1, 2";
                break;
            case 5:
                $value = "1, 2, 3, 4, 5";
                break;
        }

        return $value;
    }


    public function BtnModalPerso($crud, $titre, $class, $id)
    {
        $btn = "";
        if ($crud == "create") {
            $btn = '
            <!-- Button trigger modal -->
            <br>
            <div class="row justify-content-md-center ">
                <button type="button" id="btn-Test" class="btn ' . $class . '" data-bs-toggle="modal" data-bs-target="#exampleModal' . $crud . $id . '">
                    ' . $titre . '
                </button>
            </div>';
        } else if ($crud == "update") {
            $btn = '
            <!-- Button trigger modal -->
                <button type="button" id="btn-Test" class="btn ' . $class . '" data-bs-toggle="modal" data-bs-target="#exampleModal' . $crud . $id . '">
                    ' . $titre . '
                </button>';
        } else if ($crud == "delete") {
            $btn = '
            <!-- Button trigger modal -->
                <button type="button" id="btn-Test" class="btn ' . $class . '" data-bs-toggle="modal" data-bs-target="#exampleModal' . $crud . $id . '">
                    ' . $titre . '
                </button>';
        }
        return $btn;
    }

    public function ModalPerso($crud, $titre, $id, $nom, $prenom, $tel, $mail, $Camping, $role)
    {
        $modal =

            '
        <!-- Modal -->
        
        <div class="modal fade" id="exampleModal' . $crud . $id . '" tabindex="-1" aria-labelledby="exampleModalLabel' . $crud . '" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">

                        <div class="col">
                            <h5 class="modal-title text-center" id="exampleModalLabel' . $crud . '">' . $titre . '</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                    <div class="modal-body">
                    <form enctype="multipart/form-data" action="crud" method="post">
                        ' .
            $this->formulairePerso($crud, $id,  $nom, $prenom, $tel, $mail, $Camping, $role)
            . '
            </form>
                </div>
            </div>
        </div></div>
        
        
        ';
        return $modal;
    }

    public function formulairePerso($crud, $id,  $nom, $prenom, $tel, $mail, $Camping, $role)
    {
        $formulaireperso = "";
        

        switch ($crud) {
            case 'create':
                $formulaireperso .= '
                <input type="hidden" name="table" value="personnels"/>
                <input type="hidden" name="exec" value="create"/>';

                break;


            case 'update':
                $formulaireperso .= '
                <input type="hidden" name="table" value="personnels"/>
                <input type="hidden" name="exec" value="update"/>
                <input type="hidden" name="id_P" value="' . $id . '"/>
                <input type="hidden" name="oldCamping" value="' . $Camping . '"/>';

                break;

            case 'delete':
                $formulaireperso .= '
                
                <input type="hidden" name="table" value="personnels"/>
                <input type="hidden" name="exec" value="delete"/>
                <input type="hidden" name="id_P" value="' . $id . '"/>';


                break;
        }
        $formulaireperso .= '
                            <div class="row">
                            <div class="col-3 text-center">
                                Nom : 
                            </div>
                            <div class=" col">
                                <input class="align-center" type="text" name="nom" placeholder="Nom" value="'. $nom.'"/>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-3 text-center">
                            Prenom :  
                            </div>
                            <div class=" col">
                                <input type="text" name="prenom" placeholder="Prenom" value="'. $prenom.'">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-3 text-center">
                                Tel :  
                            </div>
                            <div class=" col">
                                <input type="text" name="tel" maxlenght ="12" placeholder="0612345678"  value="'. $tel.'">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-3 text-center">
                                Mail :  
                            </div>
                            <div class=" col">
                                <input type="text" name="mail" placeholder="exemple@exp.ex" value="'. $mail.'">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-3 text-center">
                                MDP  :
                            </div>
                            <div class=" col">
                                <input type="password" name="mdp" placeholder="Mot De Passe">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-3 text-center">
                                Photo :  
                            </div>
                            <div class=" col">
                                <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
                                <input type="file" name="Photo">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-3 text-center">
                                <label for="campings-select">Camping </label> 
                            </div>
                            <div class=" col">
                            ' . $this->ChoixCampingsCreationPerso($Camping) . '
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-3 text-center">
                            <label for="role-select">Rôle:</label> 
                            </div>
                            <div class=" col">
                                ' . $this->ChoixRolesCreationPerso($role) . '
                            </div>
                        </div>';


        switch ($crud) {
            case 'create':
                $formulaireperso .= '
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Créer</button>
                </div>';

                break;


            case 'update':
                $formulaireperso .= '
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Modifier</button>
                </div>';

                break;

            case 'delete':
                $formulaireperso .= '
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Oui Supprimer</button>
                </div>';


                break;
        }
        return $formulaireperso;
    }

    public function getNomCampingChoisi($idCamp)
    {
        $nomCamp = $this->getAllCampings();
        $i = 0;

        while ($i <= count($nomCamp)) {
            if ($nomCamp[$i]->getIdCamping() == $idCamp) {
                $nomCamp = $nomCamp[$i]->getNomCamping();
                break;
            }
            $i++;
        }

        return $nomCamp;
    }

    private function ChoixActivites($idPerso){
        include "../DAO.php";
        $value = 'Activitées
        <select name="Activite" id="activite-select">';

        $ins = $bdd->query('SELECT * FROM `activites` WHERE id_Cam = (Select id_Cam From personnels WHERE id_P = '.$idPerso.');');
        
        while ($donnee = $ins->fetch()) {
                $value .= "<option value='" . $donnee["id_Act"] . "'>" . $donnee["libelle_Act"] . "</option>";
        }

        $value .= '</select>

        ';

        return $value;

    }

private function SelectH(){
    $h="";
    for ($i=8; $i < 25; $i++) { 
        $d = $i<10?"0".$i:$i;
       $h .= '<option value="'.$d.'">'.$i.' H </option>' ;
    }
    
    return $h;
}

private function SelectM(){
    $minutes="";
    for ($i=0; $i < 60; $i+=15) { 
        $d = $i<10?"0".$i:$i;
       $minutes .= '<option value="'.$d.'">'.$i.' M </option>' ;
    }
    
    return $minutes;
}

    public function FormCreateAnimation($idPerso,$date){
        $formulaireAnimation = '
        <form enctype="multipart/form-data" action="read.php?j='.date('Y-m-d').'" method="post">
            <input type="hidden" name="table" value="animations"/>
            <input type="hidden" name="exec" value="LinkAn"/>
            <input type="hidden" name="id_P" value="' . $idPerso . '"/>
            <div class="row">
                '.$this->ChoixActivites($idPerso).'
            </div>
            <div class="row">
                Date de debut: <input type="date" name="DateDebut" value="'.$date.'" />
            </div>
            <div class="row">
                <div class="col">
                    Heure de Debut:
                </div>
                <div class="col">
                    Heure de fin:
                </div>

            </div>
            <div class="row">
            <div class="col">
                <select name="HDebut" id="horaire-de-debut">'.$this->SelectH().'</select> <select name="MDebut" id="minutes-de-debut">'.$this->SelectM().'</select>
            </div>
            <div class="col">
                <select name="HFin" id="horaire-de-fin">'.$this->SelectH().'</select> <select name="MFin" id="minutes-de-fin">'.$this->SelectM().'</select>
            </div>

        </div>
            <div class="row ">
            ㅤ
                <input type="submit" class="btn btn-primary" value="Créer"/>
            </div>
            
        </form>';

        return $formulaireAnimation;
    }

    public function AnimationParPersonnel($idPerso){
        include "../DAO.php";
        $ins = $bdd->query('SELECT * FROM animations INNER JOIN activites ON `animations`.`id_Act` = `activites`.`id_Act` INNER JOIN `campings` ON `activites`.`id_Cam` = `campings`.`id_Cam` WHERE id_P =  '. $idPerso .' ORDER BY `dateDebut_An` DESC  ;');
        $animations;
        //debug
        //var_dump($ins);
        $i=0;
        $affichage = '
                <table class="table table-bordered table-hover  table-primary table-striped">
                    <thead>
                        <tr>
                            <th>
                                Couleur
                            </th>
                            <th>
                                Titre
                            </th>
                            <th>
                                Description
                            </th>
                            <th>
                                Date
                            </th>
                            <th>
                                Heure de debut
                            </th>
                            <th>
                                Heure de fin
                            </th>
                        </tr>
                    </thead>
                    <tbody>';

        while ($donnee = $ins->fetch()) {
            //Debug
            //var_dump($donnee);
            $activite = new Activite($donnee["id_Act"],$donnee["libelle_Act"],$donnee["description_Act"],$donnee["couleur_Act"],$donnee["active_Act"],$donnee["nom_Cam"]);
            $animation = new Animation($donnee["id_An"],$donnee["id_P"],$activite,$donnee["dateDebut_An"],$donnee["dateFin_An"]);
            $animations[$i] = $animation;
            $i++;
            $affichage .= '
                        <tr>
                            <td style="background : '.$animation->getActivite()->getCouleurActivite().'; box-shadow: none"></td>
                            <td>'.$animation->getActivite()->getLibelleActivite().'</td>
                            <td>'.$animation->getActivite()->getDescriptionActivite().'</td>
                            <td>'.explode(" ",$animation->getDateDebut())[0].'</td>
                            <td>'.explode(" ",$animation->getDateDebut())[1].'</td>
                            <td>'.explode(" ",$animation->getDateFin())[1].'</td>
                        </tr>';
        }
        $affichage .= '
                    </tbody>
                </table>';
        return $affichage;
    }
}
