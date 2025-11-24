<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="php.css">
        <title> BrainBoost| Quiz</title> <!--zorgt ervoor dat dit komt te staan bij de tabblad LH SI-->
        <!--icoon te zien links bij tabblad LH (w3schools,2024)-->
        <link rel="shortcut icon" href="quizlogo.png" type="image/x-icon">

    </head>
    <body>
    <header><img src= "brainboost_foto.png"><br>
            <br>
            <button><a href="index.php">HOME</a></button>
           <input type="text" placeholder="zoeken">
           <button onclick="vraagNiveau()">Niveau invullen</button>



</header> 
        
<?php
$con = mysqli_connect("localhost","root","","po_webapp");

// controleert de verbinding met sql
if (mysqli_connect_errno()) {
  echo "Verbinding met MySQL mislukt: " . mysqli_connect_error();
  exit();
}
echo "Verbinding is gelukt!";
?>

</body>
</html>