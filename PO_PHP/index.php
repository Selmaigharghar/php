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

<script>
function vraagNiveau() {
    let niveau = prompt("Welk niveau doe je? (kies uit havo, vwo)");

    // als er niks wordt ingevuld moet het opnieuw gevraagd worden
    while (!niveau) {
        niveau = prompt("Vul een geldig niveau in (kies uit havo, vwo):");
    }

    // stuurt niveau naar PHP opslaan
    window.location.href = "opslaan.php?niveau=" + niveau; //vragen hoe we ervoor zorgen dat niet elk niveau kan
}
</script>

        </header>
<?php
session_start();

if (isset($_GET['niveau'])) {
    // strtolower zet de gebruikersinput om naar kleine letters 
    $niveau = strtolower($_GET['niveau']); 
    
    // alleen als havo of vwo zijn ingevuld moet de functie verder gaan
    if ($niveau == "havo" || $niveau == "vwo") {
        $_SESSION['niveau'] = $niveau;
        echo "Niveau opgeslagen: " . $_SESSION['niveau'];
    } else {
        echo "Ongeldig niveau. Alleen 'havo' of 'vwo' is toegestaan.";
    }
} else {
    echo "Geen niveau ontvangen."; // ik ga morgen uitzoeken waaorm het niet werkt
}
?>
    </body>
</html>