<?php
class Activite{
    protected $id;

    protected $libelle;

    protected $description;

    protected $couleur;

    protected $camping;

    protected $actif;

    public function __construct($id, $libelle, $description, $couleur, $actif, $camping)
    {
        $this->id = $id;
        $this->libelle = $libelle;
        $this->description = $description;
        $this->couleur = $couleur;
        $this->camping = $camping;
        $this->actif = $actif;
    }



    /**
     * Get the value of camping
     */ 
    public function getCampingActivite()
    {
        return $this->camping;
    }

    /**
     * Get the value of couleur
     */ 
    public function getCouleurActivite()
    {
        return $this->couleur;
    }

    /**
     * Get the value of description
     */ 
    public function getDescriptionActivite()
    {
        return $this->description;
    }

    /**
     * Get the value of libelle
     */ 
    public function getLibelleActivite()
    {
        return $this->libelle;
    }

    /**
     * Get the value of id
     */ 
    public function getIdActivite()
    {
        return $this->id;
    }

        /**
     * Get the value of actif
     */ 
    public function getActifActivite()
    {
        return $this->actif;
    }

    public function setTableauActivites($camp)
    {
        require("../DAO.php");

        $insR = $bdd->query('SELECT * FROM `activites` INNER JOIN `campings` ON `activites`.`id_Cam` = `campings`.`id_Cam` WHERE `activites`.`id_Cam` IN ('.$camp.');');
        $i = 0;
        $tableauActivites;
        while ($activite = $insR->fetch()) {
            $r = new Activite($activite["id_Act"],$activite["libelle_Act"],$activite["description_Act"],$activite["couleur_Act"],$activite["active_Act"],$activite["nom_Cam"]); 
            $tableauActivites[$i] = $r;
            $i++;
        }

        return $tableauActivites;

    }

    public function getIdCampings($camp){
        $all = $_SESSION["Personnel"]->getAllCampings();

        $allId = $all[0]->getIdCamping();
        for ($i=1; $i< count($all)  ; $i++) { 
           $allId .= ", ".$all[$i]->getIdCamping();
        }
        return $allId;
    }




    public function BtnModalActivite($activite,$titre,$class,$id)
    {
        $btn ="";
        if($activite == "create"){
            
            $btn = '
            <!-- Button trigger modal -->
            <br>
            <div class="row justify-content-md-center ">
                <button type="button" id="btn-Test" class="btn '.$class.'" data-bs-toggle="modal" data-bs-target="#exampleModal'.$activite.$id.'">
                    '.$titre.'
                </button>
            </div>';
        }else if($activite == "update"){
            $btn = '
            <!-- Button trigger modal -->
                <button type="button" id="btn-Test" class="btn '.$class.'" data-bs-toggle="modal" data-bs-target="#exampleModal'.$activite.$id.'">
                    '.$titre.'
                </button>';
        }else if($activite == "delete"){
            $btn = '
            <!-- Button trigger modal -->
                <button type="button" id="btn-Test" class="btn '.$class.'" data-bs-toggle="modal" data-bs-target="#exampleModal'.$activite.$id.'">
                    '.$titre.'
                </button>';
        }
        return $btn;
    }


    public function ModalActivite( $activite ,$titre,$id,$libelle,$description,$couleur,$camping,$class,$readonly)
    {
        $modal= 
        '<!-- Modal -->
        
        <div class="modal fade " id="exampleModal'.$activite.$id.'" tabindex="-1" aria-labelledby="exampleModalLabel'.$activite.'" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content '.$class.'">
                    <div class="modal-header">

                        <div class="col">
                            <h5 class="modal-title text-center" id="exampleModalLabel'.$activite.'">'.$titre.'</h5>
                        </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                    <form enctype="multipart/form-data" action="crud" method="post">
                        ' .
                    $this->formulaireActivite($activite,$id,$libelle,$description,$couleur,$readonly)
            . '
                    </form>
                </div>
            </div>
        </div>
        </div>
        
        
        ';
        return $modal;
    }

    private function formulaireActivite($activite,$id,$libelle,$description,$couleur,$readonly)
    {
        $formulaireActivite="";

        switch ($activite) {
            
            case 'create':
                $formulaireActivite .= '
                <input type="hidden" name="table" value="activites"/>
                <input type="hidden" name="exec" value="create"/>';

                break;
            
            
            case 'update':
                $formulaireActivite .= '
                <input type="hidden" name="table" value="activites"/>
                <input type="hidden" name="exec" value="update"/>
                <input type="hidden" name="id_Act" value="'.$id.'"/>';
                    
                 break;

                case 'delete':
                $formulaireActivite .= '
                
                <input type="hidden" name="table" value="activites"/>
                <input type="hidden" name="exec" value="delete"/>
                <input type="hidden" name="id_Act" value="'.$id.'"/>';
                        
                    break;
        }
        $formulaireActivite .= '
        <div class="row">
            <div class="col-3 text-center">
                Libelle : 
            </div>
            <div class=" col">
                <input class="align-center" type="text" name="libelle_Act" placeholder="Libelle" value="'.$libelle.'" '.$readonly.'/>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-3 text-center">
                Description : 
            </div>
            <div class=" col">
                <input class="align-center" type="text" name="description_Act" placeholder="Description" value="'.$description.'" '.$readonly.'/>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-3 text-center">
                Couleur : 
            </div>
            <div class=" col"> 
                <input class="align-center" type="color" name="couleur_Act" value="'.$couleur.'" '.$readonly.'/>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-3 text-center">
                Campings : 
            </div>
            <div class=" col">
            '.$_SESSION["Personnel"]->ChoixCampingsCreationPerso().'
            </div>
        </div>
        <br>
        <div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">'.$activite.'</button>
            </div>
        </div> ';

        return $formulaireActivite;
    }
}
?>