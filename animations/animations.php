<?php
class Animation

{
    protected $id_An;

    protected $id_P;

    protected $activite;

    protected $dateDebut;

    protected $dateFin;

    protected $nbParticipant;

    public function __construct($id_An , $id_P, $activite, $dateDebut, $dateFin)
    {
        $this->id_An = $id_An;
        $this->id_P = $id_P;
        $this->activite = $activite;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
    }

    public function getId_An(){
        return $this->id_An;
    }
    public function getId_P(){
        return $this->id_P;
    }
    public function getActivite(){
        return $this->activite;
    }
    public function getDateDebut(){
        return $this->dateDebut;
    }
    public function getDateFin(){
        return $this->dateFin;
    }



    public function AnimationParPersonnel($idPerso){
        include "../DAO.php";
        $ins = $bdd->query('SELECT * FROM animations WHERE id_P =  '. $idPerso .' ;');
        // var_dump($ins);
        // $animations;
        // $i=0;
        // while ($donnee = $ins->fetch()) {
        //     var_dump($donnee);
        //     $animations[$i] = $donnee;
        //     $i++;
        // }
        return $ins;
    }


    public function BtnModalAnimation($activite)
    {
        $titre = $this->activite->getLibelleActivite();
        $dateDebut = $this->dateDebut;
        $btn ="";
        if($activite == "create"){
            
            $btn = '
            <!-- Button trigger modal -->
            <br>
            <div class="row justify-content-md-center ">
                <button type="button" id="btn-Test" class="btn '.$this->activite->getCouleurActivite()." w-100".'" data-bs-toggle="modal" data-bs-target="#ModalAnimation'.$activite.'Id'.$this->id_An.'">
                    '.$titre.'
                </button>
            </div>';
        }else if($activite == "update"){
            $btn = '
            <!-- Button trigger modal -->
                <button type="button" id="btn-Test" class="btn '.$this->activite->getCouleurActivite()." w-100".'" data-bs-toggle="modal" data-bs-target="#ModalUpdateAnimation'.$activite.'Id'.$this->id_An.'">
                    '.$titre.'
                </button>';
        }else if($activite == "view"){
            $btn = '
            <!-- Button trigger modal -->
                <button type="button" id="btn-Test" class="btn '.$this->activite->getCouleurActivite()." w-100".'" data-bs-toggle="modal" data-bs-target="#ModalAnimation'.$activite.'Id'.$this->id_An.'">
                    '.$titre.'
                </button>';
        }else if($activite == "delete"){
            $btn = '
            <!-- Button trigger modal -->
                <button type="button" id="btn-Test" class="btn '.$this->activite->getCouleurActivite()." w-100".'" data-bs-toggle="modal" data-bs-target="#ModalAnimation'.$activite.'Id'.$this->id_An.'">
                    '.$titre.'
                </button>';

        }
        return $btn;
    }


    public function ModalAnimation( $activite)
    {
        $titre = $this->activite->getLibelleActivite();
        $dateDebut = $this->dateDebut;
        switch ($activite) {
            
            
            case 'update':
                $modal= 
                '<!-- Modal -->
                
                <div class="modal fade " id="ModalUpdateAnimation'.$activite.'Id'.$this->id_An.'" tabindex="-1" aria-labelledby="ModalAnimation'.$activite.'Id'.$this->id_An.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content '.$this->activite->getCouleurActivite()." w-100".'">
                            <div class="modal-header">
        
                                <div class="col">
                                    <h5 class="modal-title text-center" id="ModalAnimation'.$activite.'Id'.$this->id_An.'">'.$titre.'</h5>
                                </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
        
                            <div class="modal-body">
                            <form enctype="multipart/form-data" action="crud" method="post">
                           
                            </form>
                        </div>
                    </div>
                </div>
                </div>
                
                
                ';
                    
                 break;


            case 'view':
                $modal= 
                '<!-- Modal -->
                
                <div class="modal fade " id="ModalAnimation'.$activite.'Id'.$this->id_An.'" tabindex="-1" aria-labelledby="ModalAnimation'.$activite.'Id'.$this->id_An.'" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content '.$this->activite->getCouleurActivite()." w-100".'">
                            <div class="modal-header">
        
                                <div class="col">
                                    <h5 class="modal-title text-center" id="ModalAnimation'.$activite.'Id'.$this->id_An.'">'.$titre.'</h5>
                                </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
        
                            <div class="modal-body">
                            
                                ';

                                    $modal .= $this->formulaireAnimation($titre);

                            
                    $modal .= '
                            
                        </div>
                    </div>
                </div>
                </div>
                
                
                ';
                            
                break;
        }

        return $modal;
    }

    private function formulaireAnimation($titre)
    {
        $formulaireAnimation="";

        $formulaireAnimation .= '';

        $dateAAfficher = new DateTimeImmutable($this->getDateDebut());
        $formulaireAnimation .= '
        <div class="row">
            <div class="col-3 text-center">
                Description : 
            </div>
        <div class=" col-9">
            <textarea rows="4" cols="40" name="Dercription">'.$this->activite->getDescriptionActivite().'</textarea>
        </div>
    </div>
    <br>
        <div class="row">
            <div class="col-3 text-center">Date :</div> 
            <div class="col-9 text-left">'.$this->getDateForModal($dateAAfficher->format('j N m')).'</div>
        </div>
        <br>

        <br>
        <div class="row">
            <div class="col-3 text-center">De :</div> 
            <div class="col-9 text-left">'.explode(":",explode(" ",$this->getDateDebut())[1])[0].'H'.explode(":",explode(" ",$this->getDateDebut())[1])[1].' à '.explode(":",explode(" ",$this->getDateFin())[1])[0].'H'.explode(":",explode(" ",$this->getDateFin())[1])[1].'</div>
        </div>
        <br>';
        // var_dump($_SERVER);

        if (in_array($_SESSION['Personnel']->getRole()->getLibelleRole(), array("Responsable d'animation","Coordinateur", "Administration TSA"))){
            $formulaireAnimation .= '
            <div>
                <div class="modal-footer">
                <form enctype="multipart/form-data" action="" method="get">
                <input type="hidden" name="table" value="activites"/>
                <input type="hidden" name="update" value="update"/>
                <input type="hidden" name="id_An" value="'.$this->getId_An().'"/>
                <button type="submit" class="btn btn-primary">Modifier</button>
                
            </form>
                   ';
                   $formulaireAnimation .= ' 
                    <form enctype="multipart/form-data" action="crud" method="post">
                        <input type="hidden" name="table" value="activites"/>
                        <input type="hidden" name="exec" value="delete_Animation"/>
                        <input type="hidden" name="id_An" value="'.$this->getId_An().'"/>
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div> ';
        }
       

        return $formulaireAnimation;
    }


    private function getDateForModal($date){
        $JoursFrancais = ["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
        $j = $JoursFrancais[explode(" ",$date)[1]-1] ;
        $MoisFrancais =["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre",];
        $m = $MoisFrancais[explode(" ",$date)[1]-1];
        return "".$j." ".explode(" ",$date)[0]." ".$m;
    }


}