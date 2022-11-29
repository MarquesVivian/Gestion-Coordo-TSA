<?php
class Role
{
    protected $id;

    protected $libelle;

    protected $actif;


    public function __construct($id, $libelle, $actif)
    {
        $this->id = $id;
        $this->libelle = $libelle;
        $this->actif = $actif;
    }

    /**
     * Get the value of id
     */
    public function getIdRole()
    {
        return $this->id;
    }

    /**
     * Get the value of nom
     */
    public function getLibelleRole()
    {
        return $this->libelle;
    }

    /**
     * Set the value of nom
     *
     * @return  self
     */
    public function setLibelleRole($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getActifRole(){
        return $this->actif;
    }


    public function setTableauRole()
    {
        require("../DAO.php");

        $insR = $bdd->query('SELECT * FROM `roles`;');
        $i = 0;
        $tableauRole;
        while ($role = $insR->fetch()) {
            $r = new Role($role["id_R"],$role["libelle_R"],$role["active_R"]); 
            $tableauRole[$i] = $r;
            $i++;
        }

        return $tableauRole;

    }

    public function BtnModalRole($role,$titre,$class,$id)
    {
        $btn ="";
        if($role == "create"){
            $btn = '
            <!-- Button trigger modal -->
            <br>
            <div class="row justify-content-md-center ">
                <button type="button" id="btn-Test" class="btn '.$class.'" data-bs-toggle="modal" data-bs-target="#exampleModal'.$role.$id.'">
                    '.$titre.'
                </button>
            </div>';
        }else if($role == "update"){
            $btn = '
            <!-- Button trigger modal -->
                <button type="button" id="btn-Test" class="btn '.$class.'" data-bs-toggle="modal" data-bs-target="#exampleModal'.$role.$id.'">
                    '.$titre.'
                </button>';
        }else if($role == "delete"){
            $btn = '
            <!-- Button trigger modal -->
                <button type="button" id="btn-Test" class="btn '.$class.'" data-bs-toggle="modal" data-bs-target="#exampleModal'.$role.$id.'">
                    '.$titre.'
                </button>';
        }
        return $btn;
    }


    public function ModalRole($role,$titre,$id,$libelle)
    {
        $modal= 
        '<!-- Modal -->
        
        <div class="modal fade" id="exampleModal'.$role.$id.'" tabindex="-1" aria-labelledby="exampleModalLabel'.$role.'" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">

                        <div class="col">
                            <h5 class="modal-title text-center" id="exampleModalLabel'.$role.'">'.$titre.'</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                    <div class="modal-body">
                    <form enctype="multipart/form-data" action="crud" method="post">
                        ' .
            $this->formulaireRole($role,$id,$libelle)
            . '
                    
                </div>
            </div>
        </div>
        </form>
        
        ';
        return $modal;
    }

    public function formulaireRole($role,$id,$libelle)
    {

        switch ($role) {
            case 'create':
                $formulaireRole = '
                <input type="hidden" name="table" value="roles"/>
                <input type="hidden" name="exec" value="create"/>
                <div class="row">
                    <div class="col-3 text-center">
                        Libelle : 
                    </div>
                    <div class=" col">
                        <input class="align-center" type="text" name="libelle_R" placeholder="Libelle"/>
                    </div>
                </div>
                </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </div>';

                break;
            
            
            case 'update':
                $formulaireRole = '
                <input type="hidden" name="table" value="roles"/>
                <input type="hidden" name="exec" value="update"/>
                <input type="hidden" name="id_R" value="'.$id.'"/>
                <div class="row">
                    <div class="col-3 text-center">
                        Libelle : 
                    </div>
                    <div class=" col">
                        <input class="align-center" type="text" name="libelle_R" value="'.$libelle.'"/>
                    </div>
                </div>
                </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </div>';
                    
                 break;

                case 'delete':
                $formulaireRole = '
                
                <input type="hidden" name="table" value="roles"/>
                <input type="hidden" name="exec" value="delete"/>
                <input type="hidden" name="id_R" value="'.$id.'"/>
                <input type="hidden" name="libelle_R" value="'.$libelle.'"/>
                <div class="row">
                    <div class="col text-center">
                    etes vous sur de vouloir supprimer le role : '.$libelle.' ? 
                    </div>
                </div>
                </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Oui Supprimer</button>
                    </div>';
                        
                    break;
        }

        return $formulaireRole;
    }

}