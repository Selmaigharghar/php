<?php
session_start(); // Start de sessie; noodzakelijk om inloggegevens op te slaan na registratie.


$con = mysqli_connect("localhost", "root", "", "po_webapp"); // Maak verbinding met de database.
if (!$con) { die("Databasefout: " . mysqli_connect_error()); } // Toon fout als de verbinding niet goed is

$success_message = ""; // Variabele om succesmeldingen op te slaan.
$error_message = "";   // Variabele om foutmeldingen op te slaan.

// Controleert of het formulier is verzonden (via de POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1. Haal alle benodigde velden op en bescherm tegen 'undefined index' fouten
     $gebruikersnaam = $_POST['gebruikersnaam'] ?? '';
     $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_repeat = $_POST['password_repeat'] ?? '';
    $school = $_POST['school'] ?? '';
    $niveau = $_POST['niveau'] ?? '';
    $leerjaar = $_POST['leerjaar'] ?? ''; 
    $vak_naam = $_POST['vak_naam'] ?? ''; // De ingevoerde vaknaam.


    if (empty($gebruikersnaam) || empty($email) || empty($password) || empty($leerjaar) || empty($vak_naam)) {
        $error_message = "Vul alstublieft alle velden in."; // Foutmelding als  velden leeg zijn.
    } else if ($password !== $password_repeat) {
        $error_message = "Wachtwoorden komen niet overeen!"; // Fout als herhaling niet klopt.
    }

    if (empty($error_message)) {
        // 3. Controleren of gebruikersnaam of e-mail al bestaan (UNIQUE velden)
        $stmt = mysqli_prepare($con, "SELECT id FROM gebruiker WHERE email=? OR gebruikersnaam=?"); // Bereidt zoekquery voor.
        mysqli_stmt_bind_param($stmt, "ss", $email, $gebruikersnaam); // Koppeld e-mail en gebruikersnaam (strings).
        mysqli_stmt_execute($stmt); // Voert de query uit.
        mysqli_stmt_store_result($stmt); // Slaat het resultaat op om het aantal rijen te tellen.
        if (mysqli_stmt_num_rows($stmt) > 0) { // Controleert of er al een gebruiker is met deze gegevens.
            $error_message = "Gebruikersnaam of E-mailadres bestaat al.";
        }
        mysqli_stmt_close($stmt); // Sluit het statement.
    }
    
    $vak_id = null; //  vak_id.
    if (empty($error_message)) {

        $stmt = mysqli_prepare($con, "SELECT id FROM vak WHERE naam = ?"); // Zoekt het ID.
        mysqli_stmt_bind_param($stmt, "s", $vak_naam); // Koppeld de vaknaam.
        mysqli_stmt_execute($stmt); 
        $result = mysqli_stmt_get_result($stmt); // Haalt het resultaat op.
        
        if ($row = mysqli_fetch_assoc($result)) {
            $vak_id = $row['id']; // Sla het gevonden ID op.
        } else {
            $error_message = "Het vak '$vak_naam' is niet gevonden. Probeer: Wiskunde, Natuurkunde, Scheikunde of Biologie.";
        }
        mysqli_stmt_close($stmt); // Sluit het statement.
    }

    if (empty($error_message)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Maak een veilige hash van het wachtwoord.

        $stmt = mysqli_prepare($con, 
            "INSERT INTO gebruiker (gebruikersnaam, voornaam, achternaam, email, wachtwoord, school, niveau, leerjaar, vak_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        mysqli_stmt_bind_param($stmt, "ssssssssi", 
            $gebruikersnaam, $first_name, $last_name, $email, $hashed_password, $school, $niveau, $leerjaar, $vak_id);
        
        if (mysqli_stmt_execute($stmt)) {

            $_SESSION["logged_in"] = true;
            $_SESSION["username"] = $gebruikersnaam;
            $_SESSION["niveau"] = $niveau;
            $_SESSION["leerjaar"] = $leerjaar;
            $_SESSION["vak_id"] = $vak_id;
            $_SESSION["user_id"] = mysqli_insert_id($con); // Haal de zojuist ingevoegde ID op voor de sessie
            
-
            if ($vak_id == 4) { // Biologie heeft in de database ID 4
                
                // Zoek de ID van de eerste relevante quiz die past bij vak, niveau en leerjaar
                $quiz_sql = "SELECT q.id 
                            FROM quiz q
                            JOIN onderwerp o ON q.onderwerp_id = o.id
                            WHERE o.vak_id = ? AND q.niveau = ? AND q.leerjaar = ?
                            ORDER BY q.id LIMIT 1"; // Pak de quiz met de laagste ID
                
                $quiz_stmt = mysqli_prepare($con, $quiz_sql);
                mysqli_stmt_bind_param($quiz_stmt, "iss", $vak_id, $niveau, $leerjaar); // Koppel de drie filters
                mysqli_stmt_execute($quiz_stmt);
                $quiz_result = mysqli_stmt_get_result($quiz_stmt);
                
                if ($quiz_row = mysqli_fetch_assoc($quiz_result)) {
                    $target_quiz_id = $quiz_row['id'];
                    

                    header("Location: quiz.php?id=" . $target_quiz_id); // Stuurt door naar de quiz met de gevonden ID.
                    exit(); // Stopt de verdere uitvoering van dit script.
                }
                mysqli_stmt_close($quiz_stmt); // Sluit het quiz statement
            }

            $success_message = "✅ Account succesvol aangemaakt! U wordt doorgestuurd...";
            header("Refresh: 3; url=index.php"); // Wacht 3 seconden en stuur door naar het dashboard.
        } else {
            $error_message = "❌ Fout bij het aanmaken van account: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt); // Sluit het statement voor het aanmaken van de gebruiker.
    }
}
mysqli_close($con); // Sluit de databaseverbinding aan het einde van het script.
?>

<!DOCTYPE html>
<html>
<head>
    </head>
<body>
</body>
</html>

