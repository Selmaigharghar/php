<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="php.css">
    <title>BrainBoost | Quiz</title>
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
        @media screen and (max-width: 300px) {
            form { width: 90%; }
            span.psw { float: none; display: block; text-align: center; }
        }
    </style>
</head>
<body>
<form action="" method="post">
    <div class="container">
        <label for="uname"><b>Inloggen</b></label>
        <input type="text" placeholder="Uw gebruikersnaam" name="uname" required>
        <label for="psw"></label>
        <input type="password" placeholder="Uw wachtwoord" name="psw" required>
        <button type="submit">Login</button>
        <label>
            <input type="checkbox" checked="checked" name="remember"> Onthoud mij
        </label>
    </div>
</form>

<?php
session_start(); // Start de sessie

// Verbinding maken met database
$con = mysqli_connect("localhost", "root", "", "po_webapp");
if (!$con) {
    die("Databasefout: " . mysqli_connect_error());
}

// Als het formulier is verzonden
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["uname"] ?? "";
    $password = $_POST["psw"] ?? "";

    // Bereid SQL query voor
    $sql = "SELECT id, gebruikersnaam, wachtwoord, niveau, leerjaar, school 
            FROM gebruiker 
            WHERE gebruikersnaam = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    // Check of gebruiker bestaat
    if (mysqli_stmt_num_rows($stmt) === 1) {

        mysqli_stmt_bind_result($stmt, $id, $db_user, $db_pass, $niveau, $leerjaar, $school);
        mysqli_stmt_fetch($stmt);

        // Controle wachtwoord (nu plain-text)
        if ($password === $db_pass) {
            // Vul sessievariabelen
            $_SESSION["logged_in"] = true;
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $db_user;
            $_SESSION["niveau"] = $niveau;
            $_SESSION["leerjaar"] = $leerjaar;
            $_SESSION["school"] = $school;

            // Redirect naar index
            header("Location: index.php");
            exit();
        } else {
            $error = "Wachtwoord onjuist!";
        }
    } else {
        $error = "Gebruiker bestaat niet!";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($con);
?>
</body>
</html>