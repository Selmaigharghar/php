<?php session_start(); ?>
<!DOCTYPE html>
    <head>
        <link rel="stylesheet" type="text/css" href="php.css">
        <title> BrainBoost| Quiz</title> <!--zorgt ervoor dat dit komt te staan bij de tabblad LH SI-->
        <!--icoon te zien links bij tabblad LH (w3schools,2024)-->
        <link rel="shortcut icon" href="quizlogo.png" type="image/x-icon">

    </head>
    <body>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="php.css">
        <title> BrainBoost| Quiz</title> <!--zorgt ervoor dat dit komt te staan bij de tabblad LH SI-->
        <!--icoon te zien links bij tabblad LH (w3schools,2024)-->
        <link rel="shortcut icon" href="quizlogo.png" type="image/x-icon">

    </head>
    <body>
<nav class="main">
<ul>

<li><a href="#">Niveau: <?= $_SESSION['niveau'] ?? "kies" ?></a>
    <ul>
        <li><a href="niveau.php?niveau=Havo">Havo</a></li>
        <li><a href="niveau.php?niveau=Vwo">Vwo</a></li>
    </ul>
</li>

<li><a href="#">Vak: <?= $_SESSION['vak'] ?? "kies" ?></a>
    <ul>
        <li><a href="vak.php?vak=WiskundeA">Wiskunde A</a></li>
        <li><a href="vak.php?vak=WiskundeB">Wiskunde B</a></li>
    </ul>
</li>

</ul>
</nav>

        

<?php

$con = mysqli_connect("localhost","root","","po_webapp");

// controleert de verbinding met sql
if (mysqli_connect_errno()) {
  echo "Verbinding met MySQL mislukt: " . mysqli_connect_error();
  exit();
}
//echo "Verbinding is gelukt!";

?>

</body>
</html>
