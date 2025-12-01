<?php
session_start(); // Start de sessie

// Controleer of de gebruiker al is ingelogd
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: index.php"); // Stuur direct door naar home
    exit();
}

// Verbinding maken met database
$con = mysqli_connect("localhost", "root", "", "po_webapp");
if (!$con) {
    die("Databasefout: " . mysqli_connect_error());
}

$error = "";

// Als het formulier is verzonden
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username_or_email = $_POST["uname"] ?? "";
    $password = $_POST["psw"] ?? "";

    // Zoek gebruiker op gebruikersnaam OF e-mail
    $sql = "SELECT id, gebruikersnaam, wachtwoord, niveau, leerjaar, school, vak_id
            FROM gebruiker 
            WHERE gebruikersnaam = ? OR email = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username_or_email, $username_or_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt); // Haal resultaat op

    // Check of gebruiker bestaat
    if ($row = mysqli_fetch_assoc($result)) {

        if (password_verify($password, $row['wachtwoord'])) {
            // Vul sessievariabelen
            $_SESSION["logged_in"] = true;
            $_SESSION["user_id"] = $row['id'];
            $_SESSION["username"] = $row['gebruikersnaam'];
            $_SESSION["niveau"] = $row['niveau'];
            $_SESSION["leerjaar"] = $row['leerjaar'];
            $_SESSION["school"] = $row['school'];
            $_SESSION["vak_id"] = $row['vak_id']; // Slaat vak_id op

            // Redirect naar index
            header("Location: index.php");
            exit();
        } else {
            $error = "Wachtwoord onjuist!";
        }
    } else {
        $error = "Gebruiker of E-mail bestaat niet!";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($con);
?>
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