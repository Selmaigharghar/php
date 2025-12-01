<?php
session_start();

// maakt de database verbinding
$con = mysqli_connect("localhost", "root", "", "po_webapp");
if (!$con) { die("Databasefout: " . mysqli_connect_error()); } //wanneer de verbinding mislukt stopt het script

$success_message = ""; //variabele voor succesmelding
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") { //hier wordt gecontroleerd of het formulier is verzonden via POST
    // Haalt alle beodigde velden op (wat er is ingevuld)
    $gebruikersnaam = $_POST['gebruikersnaam'] ?? '';
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_repeat = $_POST['password_repeat'] ?? '';
    $school = $_POST['school'] ?? '';
    $niveau = $_POST['niveau'] ?? '';
    $leerjaar = $_POST['leerjaar'] ?? '';  
    $vak_naam = $_POST['vak_naam'] ?? ''; 

    // Wachtwoorden en leegte controleren
    if (empty($gebruikersnaam) || empty($email) || empty($password) || empty($leerjaar) || empty($vak_naam)) {
        $error_message = "Vul alstublieft alle velden in."; //als deze velde leeg zijn krijg je deze melding
    } else if ($password !== $password_repeat) { //
        $error_message = "Wachtwoorden komen niet overeen!"; // Controle of de twee wachtwoorden gelijk zijn
    }

    if (empty($error_message)) { // zorgt ervoor dat je alleen verder gaat als er nog geen fout is
        // controleren of gebruikersnaam of e-mail al bestaan 
        $stmt = mysqli_prepare($con, "SELECT id FROM gebruiker WHERE email=? OR gebruikersnaam=?"); /*dit bereidt te query veilig voor zonder SQL-injecties voor. in $stmt slaat de database de query op, wachtend totdat de echte waarde wordt ingevuld */
        mysqli_stmt_bind_param($stmt, "ss", $email, $gebruikersnaam); //Deze functie koppelt variabelen aan de ? in de prepared statement, ss btekend dat beide variabelen strings zijn
        mysqli_stmt_execute($stmt); //dit voert de query uit in de database
        mysqli_stmt_store_result($stmt); //slaat het resultaat op, hierdoor kan het aantal rijen worden gecontroleerd
        if (mysqli_stmt_num_rows($stmt) > 0) { //deze regel controleert of de gebruiker/email al bestaat
            $error_message = "Gebruikersnaam of E-mailadres bestaat al.";
        }
        mysqli_stmt_close($stmt); //sluit het statement af
    }
    
    $vak_id = null; //dit is de waarde van het id totdat er een waarde is gevonden
    if (empty($error_message)) { //kijken of er al fouten zijn
        // zoek het vak_id op basis van de ingevoerde vaknaam
        $stmt = mysqli_prepare($con, "SELECT id FROM vak WHERE naam = ?");
        mysqli_stmt_bind_param($stmt, "s", $vak_naam); //dit bind het vaknaam als string aan de query
        mysqli_stmt_execute($stmt); 
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) { //haalt één rij uit de database op als een simpel arraytje met kolomnamen erbij
            $vak_id = $row['id'];
        } else {
            $error_message = "Het vak '$vak_naam' is niet gevonden. Probeer: Wiskunde, Natuurkunde, Scheikunde of Biologie.";
        }
        mysqli_stmt_close($stmt);
    }

    if (empty($error_message)) {
        //Wachtwoord veilig opslaan 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Gebruiker toevoegen aan database
        $stmt = mysqli_prepare($con, 
            "INSERT INTO gebruiker (gebruikersnaam, voornaam, achternaam, email, wachtwoord, school, niveau, leerjaar, vak_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        mysqli_stmt_bind_param($stmt, "ssssssssi", 
            $gebruikersnaam, $first_name, $last_name, $email, $hashed_password, $school, $niveau, $leerjaar, $vak_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = " Account succesvol aangemaakt! U wordt doorgestuurd...";
            
            $_SESSION["logged_in"] = true;
            $_SESSION["username"] = $gebruikersnaam;
            $_SESSION["niveau"] = $niveau;
            $_SESSION["leerjaar"] = $leerjaar;
            $_SESSION["vak_id"] = $vak_id;
            
            // Haalt de zojuist ingevoegde ID op voor de sessie
            $_SESSION["user_id"] = mysqli_insert_id($con);
            
            header("Refresh: 3; url=index.php"); // Wacht 3 seconden en stuur door
        } else {
            $error_message = " Fout bij het aanmaken van account: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    }
}
mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="php.css"> <title>BrainBoost | Registratie</title>
    <link rel="shortcut icon" href="quizlogo.png" type="image/x-icon">

    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        form {
            width: 400px;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
        }

        input[type=text], input[type=password], select {
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
        .message { text-align: center; margin-top: 15px; font-weight: bold; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>

<div>
    <form action="" method="POST">
        <h2>Nieuw account aanmaken</h2>
        
        <input type="text" name="gebruikersnaam" placeholder="Gebruikersnaam (Uniek)" required> 
        <input type="text" name="first_name" placeholder="Voornaam" required>
        <input type="text" name="last_name" placeholder="Achternaam" required>
        <input type="text" name="email" placeholder="E-mailadres (Uniek)" required>
        <input type="password" name="password" placeholder="Wachtwoord" required>
        <input type="password" name="password_repeat" placeholder="Herhaal wachtwoord" required>
        
        <input type="text" name="school" placeholder="School" required>
        
        <select name="niveau" required>
            <option value="">Kies niveau</option>
            <option value="HAVO">HAVO</option>
            <option value="VWO">VWO</option>
        </select>
        
        <select name="leerjaar" required>
            <option value="">Kies leerjaar</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
        </select>

        <input type="text" name="vak_naam" placeholder="Voorkeursvak (bv. Wiskunde)" required>

        <button type="submit">Account aanmaken</button>
    </form>
    
    <?php if (!empty($error_message)): ?>
        <p class="message error"><?= htmlspecialchars($error_message) ?></p>
    <?php elseif (!empty($success_message)): ?>
        <p class="message success"><?= htmlspecialchars($success_message) ?></p>
    <?php endif; ?>
    
    <p style="text-align: center; margin-top: 15px;"><a href="Inlogscherm.php" style="color: rgb(25, 29, 153); text-decoration: underline;">Terug naar inloggen</a></p>
</div>

</body>
</html>
