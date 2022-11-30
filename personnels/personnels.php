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

            $new_tel = "";
            if ($p["num_Tel_P"] != null && $p["num_Tel_P"] != "") {
                for ($i = 0; $i < strlen($p["num_Tel_P"]); $i += 2) {
                    $new_tel .= " " . substr($p["num_Tel_P"], $i, 2);
                }
            } else {
                $new_tel = "N° tel";
            }
            $p["num_Tel_P"] = $new_tel;
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
            if ($personnel->role->getIdRole() == 1) {
                $bgImgCard = "text-dark bg-info";
            } else if ($personnel->role->getIdRole() == 2) {
                $bgImgCard = "text-white bg-danger";
            } else if ($personnel->role->getIdRole() == 3) {
                $bgImgCard = "";
            } else if ($personnel->role->getIdRole() == 4) {
                $bgImgCard = "text-white bg-success";
            } else if ($personnel->role->getIdRole() == 5) {
                $bgImgCard = "text-white bg-dark";
            }


            $card .= "<div class='col'>
                        <a href='../personnels/' style='text-decoration : none; color : black'>
                            <div class='card arrondi bg-image hover-overlay' style='width: 18rem; '>
                            
                                <div class='" . $bgImgCard . " arrondi-top'>
                                <h5 class='text-center'>" . $personnel->role->getLibelleRole() . "  </h5>
                                    <img src='" . $p["photo_P"] . "' class='card-img-top text-dark bg-light img-card-perso ' alt='...'>
                                    </div>
                                    <div class='card-body text-center'>

                                        <h5 class='card-title border border-primary'>" . $personnel->getNom() . "</h5>
                                        <h5 class='card-title border border-primary'>" . $personnel->getPrenom() . "</h5>
                                        <h5 class='card-title border border-primary'>" . str_replace("@", "<br>@", $personnel->getEmail())  . "</h5>
                                        <h5 class='card-title border border-primary'>" . $personnel->getNumTel() . "</h5>
                                        </a>";

            if (in_array($_SESSION['Personnel']->getRole()->getIdRole(), array(2, 4, 5))) {
                $card .= $this->BtnModalPerso("update","Modif","btn-info col-lg-5",$personnel->getId());
                $card .=$this->ModalPerso("update","Modification d'un Personnel",$personnel->getId(),$personnel->getNom(),$personnel->getPrenom(),$personnel->getNumTel(),$personnel->getEmail(),"","");
                if (in_array($_SESSION['Personnel']->getRole()->getIdRole(), array(4, 5))) {
                    $card .= $this->BtnModalPerso("delete","Supp","btn-danger col-lg-5",$personnel->getId());
                    $card .=$this->ModalPerso("delete","Suppression d'un Personnel",$personnel->getId(),$personnel->getNom(),$personnel->getPrenom(),$personnel->getNumTel(),$personnel->getEmail(),"","");
                }
            }
            $card .= "
                                </div>
                            </div>
                        
                            <div
                            class='mask'
                            style='
                              background: linear-gradient(
                                45deg,
                                rgba(29, 236, 197, 0.5),
                                rgba(91, 14, 214, 0.5) 100%
                              );
                            '
                          ></div>A</div>";

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
        WHERE `personnels`.`active_P` = 1 AND `Campings`.`id_Cam` IN (' . $idCamp . ') 
        GROUP BY `personnels`.`id_P`
        ORDER BY `personnels`.`id_R` DESC ;');
        $this->setCardsPersonnels($insPersonnels);
    }


    /**SELECT personnels.id_P, nom_P, prenom_P, num_Tel_P, email_P, photo_P, id_R, libelle_R, campings.id_Cam, nom_Cam, personnels.active_P from personnels INNER JOIN roles on roles.id_R = personnels.fk_R_P INNER JOIN travaille on travaille.id_P = personnels.id_P INNER JOIN campings ON campings.id_Cam = travaille.id_Cam WHERE id_R in (1,2) AND personnels.active_P = 1 AND campings.active_Cam = 1; */

    public function CalendarPerso()
    {
    }


    public function ChoixCampingsCreationPerso()
    {
        include "../DAO.php";
        $value = '
        <select name="campings" id="camping-select">';

        $ins = $bdd->query('SELECT `id_Cam`, `nom_Cam` FROM `campings` WHERE `id_Cam` IN (' . $this->ValueCampings() . ') ;');
        $i = 0;
        while ($donnee = $ins->fetch()) {
            $value .= "<option value='" . $donnee["id_Cam"] . "'>" . $donnee["nom_Cam"] . "</option>";
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



    private function ChoixRolesCreationPerso()
    {
        include "../DAO.php";
        $value = '
                <select name="roles"id="roles-select">';

        $ins = $bdd->query('SELECT * FROM roles WHERE id_R IN (' . $this->ValueRoles() . ');');
        while ($donnee = $ins->fetch()) {
            $value .= "<option value='" . $donnee["id_R"] . "'>" . $donnee["libelle_R"] . "</option>";
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

            case 4:
                $value = "1, 2";
                break;
            case 5:
                $value = "1, 2, 3, 4,5";
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
                <input type="hidden" name="exec" value="creation"/>';

                break;


            case 'update':
                $formulaireperso .= '
                <input type="hidden" name="table" value="campings"/>
                <input type="hidden" name="exec" value="update"/>
                <input type="hidden" name="id_P" value="' . $id . '"/>';

                break;

            case 'delete':
                $formulaireperso .= '
                
                <input type="hidden" name="table" value="campings"/>
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
                            ' . $this->ChoixCampingsCreationPerso() . '
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-3 text-center">
                            <label for="role-select">Rôle:</label> 
                            </div>
                            <div class=" col">
                                ' . $this->ChoixRolesCreationPerso() . '
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
        $a = 0;

        while ($i <= count($nomCamp)) {
            if ($nomCamp[$i]->getIdCamping() == $idCamp) {
                $nomCamp = $nomCamp[$i]->getNomCamping();
                break;
            }
            $i++;
        }

        return $nomCamp;
    }
}
