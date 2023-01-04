<?php
class Camping{
    protected $id;

    protected $nom;

    protected $actif;

    public function __construct($id, $nom, $actif)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->actif = $actif;
    }



    /**
     * Get the value of actif
     */ 
    public function getActifCamping()
    {
        return $this->actif;
    }

    /**
     * Set the value of actif
     *
     * @return  self
     */ 
    public function setActifCamping($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get the value of nom
     */ 
    public function getNomCamping()
    {
        return $this->nom;
    }

    /**
     * Set the value of nom
     *
     * @return  self
     */ 
    public function setNomCamping($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getIdCamping()
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

    public function setTableauCampings()
    {
        require("../DAO.php");

        $Campings = $bdd->query('SELECT * FROM `campings`;');
        $i = 0;
        $tableauCampings;
        while ($camping = $Campings->fetch()) {
            $c = new Camping($camping["id_Cam"],$camping["nom_Cam"],$camping["active_Cam"]); 
            $tableauCampings[$i] = $c;
            $i++;
        }

        return $tableauCampings;

    }

    public function BtnModalCamping($crud,$titre,$class,$id)
    {
        $btn ="";
        if($crud == "create"){
            $btn = '
            <!-- Button trigger modal -->
            <br>
            <div class="row justify-content-md-center ">
                <button type="button" id="btn-Test" class="btn '.$class.'" data-bs-toggle="modal" data-bs-target="#exampleModal'.$crud.$id.'">
                    '.$titre.'
                </button>
            </div>';
        }else if($crud == "update"){
            $btn = '
            <!-- Button trigger modal -->
                <button type="button" id="btn-Test" class="btn '.$class.'" data-bs-toggle="modal" data-bs-target="#exampleModal'.$crud.$id.'">
                    '.$titre.'
                </button>';
        }else if($crud == "delete"){
            $btn = '
            <!-- Button trigger modal -->
                <button type="button" id="btn-Test" class="btn '.$class.'" data-bs-toggle="modal" data-bs-target="#exampleModal'.$crud.$id.'">
                    '.$titre.'
                </button>';
        }
        return $btn;
    }


    public function ModalCamping($crud,$titre,$id,$libelle)
    {
        $modal= 
        '<!-- Modal -->
        
        <div class="modal fade" id="exampleModal'.$crud.$id.'" tabindex="-1" aria-labelledby="exampleModalLabel'.$crud.'" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">

                        <div class="col">
                            <h5 class="modal-title text-center" id="exampleModalLabel'.$crud.'">'.$titre.'</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                    <div class="modal-body">
                    <form enctype="multipart/form-data" action="crud" method="post">
                        ' .
            $this->formulaireCamping($crud,$id,$libelle)
            . '
                    
                </div>
            </div>
        </div>
        </form>
        
        ';
        return $modal;
    }

    public function formulaireCamping($crud,$id,$libelle)
    {

        switch ($crud) {
            case 'create':
                $formulaireCamping = '
                <input type="hidden" name="table" value="campings"/>
                <input type="hidden" name="exec" value="create"/>
                <div class="row">
                    <div class="col-3 text-center">
                        Libelle : 
                    </div>
                    <div class=" col">
                        <input class="align-center" type="text" name="nom_Cam" placeholder="Libelle"/>
                    </div>
                </div>
                </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </div>';

                break;
            
            
            case 'update':
                $formulaireCamping = '
                <input type="hidden" name="table" value="campings"/>
                <input type="hidden" name="exec" value="update"/>
                <input type="hidden" name="id_Cam" value="'.$id.'"/>
                <div class="row">
                    <div class="col-3 text-center">
                        Libelle : 
                    </div>
                    <div class=" col">
                        <input class="align-center" type="text" name="nom_Cam" value="'.$libelle.'"/>
                    </div>
                </div>
                </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </div>';
                    
                 break;

                case 'delete':
                $formulaireCamping = '
                
                <input type="hidden" name="table" value="campings"/>
                <input type="hidden" name="exec" value="delete"/>
                <input type="hidden" name="id_Cam" value="'.$id.'"/>
                <input type="hidden" name="nom_Cam" value="'.$libelle.'"/>
                <div class="row">
                    <div class="col text-center">
                    etes vous sur de vouloir supprimer le camping : '.$libelle.' ? 
                    </div>
                </div>
                </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Oui Supprimer</button>
                    </div>';
                        
                    break;
        }

        return $formulaireCamping;
    }
}


?>