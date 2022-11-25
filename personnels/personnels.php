<?php
class Personnel
{
    protected $id;

    protected $nom;

    protected $prenom;

    protected $numTel;

    protected $email;

    protected $Campings;

    protected $idRole;

    protected $nomRole;

    protected $photo;

    protected array $cardsPersonnels;

    public array $personnelsArray;

    public function __construct($id, $nom, $prenom, $photo, $numTel, $email,  $idRole, $arrayCampings, $nomRole)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->photo = $photo;
        $this->numTel = $numTel;
        $this->email = $email;
        $this->idRole = $idRole;
        $this->Campings = $arrayCampings;
        $this->nomRole = $nomRole;
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
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
    public function getCampings()
    {
        return $this->Campings;
    }

    /**
     * Set the value of Campings
     *
     * @return  self
     */
    public function setCampings($Campings)
    {
        $this->Campings = $Campings;

        return $this;
    }

    /**
     * Get the value of idRole
     */
    public function getIdRole()
    {
        return $this->idRole;
    }

    /**
     * Set the value of idRole
     *
     * @return  self
     */
    public function setIdRole($idRole)
    {
        $this->idRole = $idRole;

        return $this;
    }

    /**
     * Get the value of cardsPersonnels
     */
    public function getCardsPersonnels()
    {
        return $this->cardsPersonnels;
    }

    public function getNomRole()
    {
        return $this->nomRole;
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
        $card="AUCUN PERSONNEL";
        while ($p = $insPersonnels->fetch()) {
            
            //si il n'y a pas d'image
            if ($p["photo_P"] == '' || !file_exists($p["photo_P"])) {
                $p["photo_P"] = "img/profile-user.png";
            }
            
            //$p["photo_P"] = ($p["photo_P"] != '') ?  $p["photo_P"]  : "img\\123.jpg";

            $insP = $bdd->query('SELECT * FROM `travaille` INNER JOIN `campings` on `travaille`.`id_Cam` = `campings`.`id_Cam` WHERE `id_P` = ' . $p["id_P"] . ';');
            $i = 0;
            $arrayCampings;
            while ($camPerso = $insP->fetch()) {
                $arrayCampings["id_Cam"][$i] = $camPerso["id_Cam"];
                $arrayCampings["nom_Cam"][$i] = $camPerso["nom_Cam"];
                $i++;
            }


            $personnel = new Personnel($p["id_P"], $p["nom_P"], $p["prenom_P"], $p["photo_P"], $p["num_Tel_P"], $p["email_P"], $p["id_R"], $arrayCampings, $p["libelle_R"]);
            $this->personnelsArray[$personnel->getId()] = $personnel;
            //var_dump( $this->personnelsArray);

            $personnels[$iterator] = $personnel;
            if ($row == 3) {
                $card .= "</div>
                    <div class='row' style='margin-bottom: 20px;'>";

                $row = -1;
            } elseif ($row == 4) { //premier passage
                $card = "
                <div class='row' style='margin-bottom: 20px;'>";
                $row = -1;
            }
            $personnel->setNumTel($p["num_Tel_P"]  != "" || $p["num_Tel_P"] != null ? $p["num_Tel_P"] : " ");

            $bgImgCard ="";
            if ($personnel->getIdRole() == 1) {
                $bgImgCard = "text-dark bg-info";
            }else if ($personnel->getIdRole() == 2) {
                $bgImgCard = "text-white bg-danger";
            }else if ($personnel->getIdRole() == 3) {
                $bgImgCard = "";
            }else if ($personnel->getIdRole() == 4) {
                $bgImgCard = "text-white bg-success";
            }else if ($personnel->getIdRole() == 5) {
                $bgImgCard = "text-dark bg-info";
            }


            $card .= "<div class='col'>
                        <a href='../personnels/#' style='text-decoration : none; color : black'>
                            <div class='card' style='width: 18rem;'>
                                <div class='".$bgImgCard."'>
                                    <img src='" . $p["photo_P"] . "' class='card-img-top text-dark bg-light ' style='left: 55px;height: 210px;width: 180px;/*1*/position: relative;/*2*/overflow: hidden;/*3*/clip-path: ellipse(49% 40%);' alt='...'>
                                    </div>
                                    <div class='card-body text-center'>

                                        <h5 class='card-title border border-primary'>" . $personnel->getNom() . "</h5>
                                        <h5 class='card-title border border-primary'>" . $personnel->getPrenom() . "</h5>
                                        <h5 class='card-title border border-primary'>" . $personnel->getEmail() . "</h5>
                                        <h5 class='card-title border border-primary'>" . $personnel->getNumTel() . "</h5>
                                        <form  action='crud/envoyebdd.php' method='post'>

                                            <input type='hidden' name='table' value='personnels'/>";

            if (in_array($_SESSION['Personnel']->getIdRole(), array(2, 4, 5))) {
                            $card .= "  <input type='hidden' name='exec' value='modif'/>
                                        <input type='submit' class='btn btn-primary' value='Modifier'>";
                if (in_array($_SESSION['Personnel']->getIdRole(), array(5))) {
                            $card .="   <input type='hidden' name='exec' value='suppression'/>
                                        <input type='submit' class='btn btn-danger' value='Suprim'>";
                }
            }
            //var_dump($personnel);
            $card .= "                  <input type='hidden' name='Personnel' value='" . $personnel->getId() . "'/>
                                    </form>
                                </div>
                            </div>
                        </a>
                    </div>";

            $iterator++;
            $row++;
            $this->cardsPersonnels[$personnel->getId()] = $card;
        }
        $card .= "</div>";
        echo $card;
    }




    //fonction qui retourne un tableau de personnel (TOUS)
    public function toutPersonnels()
    {
        include "../DAO.php";
        $insPersonnels = $bdd->query('SELECT `personnels`.`id_P`, `nom_P`, `prenom_P`, `photo_P`, `num_Tel_P`, `email_P`, `personnels`.`id_R`, `libelle_R` from `personnels`
        RIGHT JOIN `roles` on `personnels`.`id_R` = `roles`.`id_R`
        INNER JOIN `travaille` ON `personnels`.`id_P` = `travaille`.`id_P`
        INNER JOIN `Campings` ON `travaille`.`id_Cam` = `Campings`.`id_Cam`
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
        var_dump($insPersonnels);
        $this->setCardsPersonnels($insPersonnels);
    }


    /**SELECT personnels.id_P, nom_P, prenom_P, num_Tel_P, email_P, photo_P, id_R, libelle_R, campings.id_Cam, nom_Cam, personnels.active_P from personnels INNER JOIN roles on roles.id_R = personnels.fk_R_P INNER JOIN travaille on travaille.id_P = personnels.id_P INNER JOIN campings ON campings.id_Cam = travaille.id_Cam WHERE id_R in (1,2) AND personnels.active_P = 1 AND campings.active_Cam = 1; */

    public function CalendarPerso()
    {
    }



    public function ModalCreationPerso()
    {
        echo '
        <!-- Button trigger modal -->
        <button type="button" id="btn-Test" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            +
        </button>

        <!-- Modal -->
        <form enctype="multipart/form-data" action="crud/envoyebdd.php" method="post">
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">

                        <div class="col">
                            <h5 class="modal-title text-center" id="exampleModalLabel">Création de personnel</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                    <div class="modal-body">
                        ' .
            $this->creationPersonnel()
            . '
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </div>
                </div>
            </div>
        </div>
        
        </form>
        ';
    }



    public function creationPersonnel()
    {

        $creationPersonnel = '
        <input type="hidden" name="table" value="personnels"/>
        <input type="hidden" name="exec" value="creation"/>
        <div class="row">
            <div class="col-3 text-center">
                Nom : 
            </div>
            <div class=" col">
                <input class="align-center" type="text" name="nom" placeholder="Nom"/>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-3 text-center">
            Prenom :  
            </div>
            <div class=" col">
                <input type="text" name="prenom" placeholder="Prenom">
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-3 text-center">
                Tel :  
            </div>
            <div class=" col">
                <input type="text" name="tel" placeholder="0601234567" maxlenght ="12">
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-3 text-center">
                Mail :  
            </div>
            <div class=" col">
                <input type="text" name="mail" placeholder="exemple@exemple.ex" maxlenght ="12">
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-3 text-center">
                MDP  :
            </div>
            <div class=" col">
                <input type="password" name="mdp">
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
            '.$this->ChoixCampingsCreationPerso().'
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-3 text-center">
            <label for="role-select">Rôle:</label> 
            </div>
            <div class=" col">
                '.$this->ChoixRolesCreationPerso().'
            </div>
        </div>';

        return $creationPersonnel;
    }

    public function ChoixCampingsCreationPerso()
    {
        $value = '
        <select name="campings" id="camping-select">';
        include "../DAO.php";
        $ins = $bdd->query('SELECT `id_Cam`, `nom_Cam` FROM `campings` WHERE `id_Cam` IN (' . $this->ValueCampings() . ') ;');
        while ($donnee = $ins->fetch()) {
            $value .= "<option value='" . $donnee["id_Cam"] . "'>" . $donnee["nom_Cam"] . "</option>";
        }

        $value .= "
        </select>
        ";

        return $value;
    }

    public function ValueCampings()
    {
        $cam = 1;
        $value = $_SESSION["Personnel"]->getCampings()["id_Cam"][0];
        while ($cam < count($_SESSION["Personnel"]->getCampings()["id_Cam"])) {
            $value .= ', ' . $_SESSION["Personnel"]->getCampings()["id_Cam"][$cam];
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

        switch ($_SESSION["Personnel"]->getIdRole()) {

            case 4:
                $value = "1, 2";
                break;
            case 5:
                $value = "1, 2, 3, 4";
                break;
        }

        return $value;
    }

}
