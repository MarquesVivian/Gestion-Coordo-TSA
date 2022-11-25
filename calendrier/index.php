<html>

<body>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../personnels/style.css" rel="stylesheet">



    <?php


    include("../navBar.php");

    require_once("calendrier.php");
    $calendrier = new calendrier();
    
    //On vérifie si $_GET["j"] existe  et qu'il est bien une date
    if (!empty($_GET["j"]) && $calendrier->isValid($_GET["j"])) {
        $calendrier->afficheTableau();
    } else {
        header("Location: http://testcoordo/calendrier/index.php?j=".date('Y-m-d')."");
    }
    ?>




</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>