<?php
class Personnel
{
    protected $id;

    protected $nom;

    protected $prenom;

    protected $numTel;

    protected $email;

    protected $idCamping;

    protected $idRole;

    protected $photo;

    protected array $tableauPersonnels;

    public function __construct($id, $nom, $prenom, $numTel, $email, $photo, $idCamping, $idRole)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->numTel = $numTel;
        $this->email = $email;
        $this->photo = $photo;
        $this->idCamping = $idCamping;
        $this->idRole = $idRole;

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
     * Get the value of idCamping
     */ 
    public function getIdCamping()
    {
        return $this->idCamping;
    }

    /**
     * Set the value of idCamping
     *
     * @return  self
     */ 
    public function setIdCamping($idCamping)
    {
        $this->idCamping = $idCamping;

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
     * Get the value of tableauPersonnels
     */ 
    public function getTableauPersonnels()
    {
        return $this->tableauPersonnels;
    }

    /**
     * Set the value of tableauPersonnels
     *
     * @return  self
     */ 
    public function setTableauPersonnels($insPersonnels)
    {

        $iterator =0;
        $row = 4;
        while ($p = $insPersonnels->fetch()) {
            $p["photo"] = ($p["photo"]!='') ?  $p["photo"]  :"img\\123.jpg";
            $personnel = new Personnel($p["id_P"],$p["nom_P"],$p["prenom_P"],$p["num_Tel_P"],$p["email_P"],$p["photo"],$p["fk_Cam_P"],$p["fk_R_P"],);
            $personnels[$iterator] = $personnel;
            if($row == 3){
                echo"</div>
                <div class='row' style='
                margin-bottom: 20px;
            '>";

                $row=-1;

            }elseif($row == 4){//premier passage
                echo"
                <div class='row' style='
                margin-bottom: 20px;
            '>";

                $row=-1;
            }
            echo"

    
                <div class='col'>
                    <div class='card' style='width: 18rem;'>
                        <img src='".$p["photo"]."' class='card-img-top' style='height: 170px;/*1*/ position: relative;/*2*/ overflow: hidden;/*3*/ top: 10px; clip-path:ellipse(30% 50%);' alt='...'>
                        <div class='card-body text-center'>
                            <h5 class='card-title border border-primary'>".$p["nom_P"]."</h5>
                            <h5 class='card-title border border-primary'>".$p["prenom_P"]."</h5>
                            <h5 class='card-title border border-primary'>".$p["email_P"]."</h5>
                            <h5 class='card-title border border-primary'>".$p["num_Tel_P"]."</h5>
                            <a href='#' class='btn btn-primary'>Modifier</a>
                            <form  action='crud/envoyebdd.php' methode='post'>
                            <input type='hidden' name='table' value='personnels'/>
                            <input type='hidden' name='exec' value='suppression'/>
                            <input type='hidden' name='id_P' value='".$p["id_P"]."'/>
                            <input type='submit' class='btn btn-danger' value='Suprim'>
                            </form>
                        </div>
                    </div>
                </div>
    

            ";
            $iterator++;
            $row++;
        }
        echo"</div>";

        $this->tableauPersonnels = $personnels;
        $_SESSION["TPersonnels"] = $personnels;
        

        return $this;
    }

    #endregion


//fonction qui retourne un tableau de personnel (TOUS)
public function toutPersonnels(){
    include "../DAO.php";
    $insPersonnels = $bdd->query('SELECT `id_P`,`nom_P`,`prenom_P`,`num_Tel_P`,`email_P`,`photo`,`fk_Cam_P`,`fk_R_P` 
    FROM `personnels` 
    WHERE `active` = 1 
    ORDER BY personnels.id_P;');

    $this->setTableauPersonnels($insPersonnels);
}


//fonction qui retourne un tableau de personnel en fonction du camping du RA
public function touPersonnels($idCamp){
    include "../DAO.php";
    $insPersonnels = $bdd->query('SELECT `id_P`,`nom_P`,`prenom_P`,`num_Tel_P`,`email_P`,`photo`,`fk_Cam_P`,`fk_R_P` 
    FROM `personnels`
     WHERE `active` = 1 
     AND `fk_Cam_P` ='.$idCamp.' 
     ORDER BY personnels.id_P;');

    $this->setTableauPersonnels($insPersonnels);
}


public function creationPersonnel(){
    include "../DAO.php";

    echo'
    
    <form  action="crud/envoyebdd.php" methode="post">
    <input type="hidden" name="table" value="personnels"/>
    <input type="hidden" name="exec" value="creation"/>
        Nom : <input type="text" name="nom" placeholder="Nom"/><br>
        Prenom : <input type="text" name="prenom" placeholder="Prenom"><br>
        tel : <input type="text" name="tel" maxlenght ="12"><br>
        mail : <input type="text" name="mail" maxlenght ="12"><br>
        mdr : <input type="text" name="mdp"><br>
        <label for="campings-select">Choisir un camping</label>
        <select name="campings" id="camping-select">
        ';

        $ins = $bdd->query('SELECT * FROM campings;');
        var_dump($ins);
        while ($donnee=$ins->fetch( )) {
        echo"<option value='".$donnee["id_Cam"]."'>".$donnee["nom_Cam"]."</option>";
    }


        echo'
        </select>
        <br>
        <label for="role-select">Choisir un rôle:</label>
        <select name="roles"id="roles-select">
        ';

$ins =$bdd->query('SELECT * FROM roles;');
while ($donnee=$ins->fetch( )) {
        echo"<option value='".$donnee["id_R"]."'>".$donnee["libelle_R"]."</option>";
    }


echo'
        </select>
    </input>
    <input type="submit" value="Envoyer">
    </form>
';

}




}
?>