<?php
   try{
      $pdo=new PDO("mysql:host=localhost;dbname=testgestioncoordo","root","");
   }
   catch(PDOException $e){
      echo $e->getMessage();
   }
?>