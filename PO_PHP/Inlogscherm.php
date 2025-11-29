<?php
session_start(); // Dit start de PHP-sessie

// CONTROLE: Controleert of de gebruiker al is ingelogd.
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: index.php"); // Stuurt je wanneer je bent ingelogd direct door naar de hoofdpagina
    exit(); // Stopt de verdere verwerking van dit script
}

// Zorgt voor een verbinding met de database
$con = mysqli_connect("localhost", "root", "", "po_webapp");
if (!$con) {
     die("Databasefout: " . mysqli_connect_error()); // Als de verbinding mislukt, stopt de code en zie je een foutmelding
}

$error = ""; // Initialiseert een lege variabele om eventuele inlgfouten in op te slaan

// Controleert of het de inlogformuliergegevens zijn verzonden via de POST-methode
if ($_SERVER["REQUEST_METHOD"] === "POST") {

     $username_or_email = $_POST["uname"] ?? ""; // leest de ingevulde gebruikersnaam of e-mail uit het formulier
     $password = $_POST["psw"] ?? ""; // Leest het ingevulde wachtwoord uit het formulier

    // VEILIGE QUERY: Selecteert alle benodigde kolommen om in te loggen en de sessie te vullen.
     $sql = "SELECT id, gebruikersnaam, wachtwoord, niveau, leerjaar, school, vak_id
     FROM gebruiker 
     WHERE gebruikersnaam = ? OR email = ?"; // Zoekt de gebruiker op basis van ofwel de gebruikersnaam of het e-mailadres
     $stmt = mysqli_prepare($con, $sql); // Bereid de SQL-query veilig voor
     mysqli_stmt_bind_param($stmt, "ss", $username_or_email, $username_or_email); // Koppeld de ingevoerde waarden (twee strings)aan de query
     mysqli_stmt_execute($stmt); // Voert de zoekactie uit
     $result = mysqli_stmt_get_result($stmt); // Haalt het resultaat van de zoekopdracht op

    // Controleert of de query een rij met gebruikersgegevens heeft teruggegeven
     if ($row = mysqli_fetch_assoc($result)) { // Als er een gebruiker is gevonden, wordt de data in $row geplaatst

        // BEVEILIGING: Vergelijkt het ingevoerde wachtwoord met de veilige 'hash' uit de database
     if (password_verify($password, $row['wachtwoord'])) { // Controleert of de hash en het ingevoerde wachtwoord matchen
            // Als het wachtwoord klopt, worden de sessievariabelen gevuld
         $_SESSION["logged_in"] = true; // Markering dat de gebruiker succesvol is ingelogd
             $_SESSION["user_id"] = $row['id']; // Slaat de unieke ID van de gebruiker op
             $_SESSION["username"] = $row['gebruikersnaam']; // Slaat de gebruikersnaam op
             $_SESSION["niveau"] = $row['niveau']; // Slaat het niveau (HAVO/VWO) op
             $_SESSION["leerjaar"] = $row['leerjaar']; // Slaat het leerjaar op
             $_SESSION["school"] = $row['school']; // Slaat de schoolnaam op
             $_SESSION["vak_id"] = $row['vak_id']; // Slaat het ID van het favoriete vak op (voor index.php)

            // Stuurt de gebruiker door naar de hoofdpagina
         header("Location: index.php"); 
         exit(); // Stopt de verdere code-uitvoering
         } else {
         $error = "Wachtwoord onjuist!"; // Stelt de foutmelding in als het wachtwoord verkeerd is
         }
 } else {
     $error = "Gebruiker of E-mail bestaat niet!"; // Stelt de foutmelding in als er geen gebruiker is gevonden
     }

     mysqli_stmt_close($stmt); // Sluit het statement om databasebronnen vrij te geven
}

mysqli_close($con); // Sluit de databaseverbinding
?>
<!DOCTYPE html>
<html>
<head>
    </head>
<body>
    </body>
</html>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="php.css"> 
    <title>BrainBoost | Inloggen</title>
    <link rel="shortcut icon" href="quizlogo.png" type="image/x-icon">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        form {
            width: 350px;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
        }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }
        button {
            background-color: rgb(25, 29, 153);
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        button:hover {
            opacity: 0.9;
        }
        .container { padding: 10px 0; }
        span.psw { float: right; margin-top: 10px; }
        .bottom-container { padding-top: 15px; }
        .bottom-container a button { background-color: #ed7eb4; } /* Kleur aanmeldknop */
    </style>
</head>
<body>
<form action="" method="post">
    <div class="container">
        <label for="uname"><b>Inloggen</b></label>
        <input type="text" placeholder="Gebruikersnaam of E-mail" name="uname" required>
        <label for="psw"></label>
        <input type="password" placeholder="Uw wachtwoord" name="psw" required>
        <button type="submit">Login</button>
        <label>
            <input type="checkbox" checked="checked" name="remember"> Onthoud mij
        </label>
        <div class="bottom-container">
            <div class="row">
                <div class="col">
                    <a href="aanmeldscherm.php"><button type="button" style="width:100%;">Aanmelden</button></a>
                </div>
            </div>
        </div>
        <?php if (!empty($error)): ?>
            <p style="color: red; text-align: center; margin-top: 10px;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
    </div>
</form>
</body>
</html>