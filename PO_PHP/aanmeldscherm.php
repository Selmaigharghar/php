<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>BrainBoost | Registratie</title>
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

        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background: rgba(0,0,0,0.6);
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            border-radius: 10px;
            padding: 20px;
            width: 450px;
            animation: fadeIn 0.3s;
        }

        .close {
            float: right;
            font-size: 30px;
            cursor: pointer;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: scale(0.9);}
            to {opacity: 1; transform: scale(1);}
        }
    </style>
</head>
<body>

<!-- REGISTRATIE FORM -->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('registerModal').style.display='none'">&times;</span>
        <h2>Nieuwe account aanmaken</h2>
        <form action="aanmeldscherm.php" method="POST">
            <input type="text" name="first_name" placeholder="Voornaam" required>
            <input type="text" name="last_name" placeholder="Achternaam" required>
            <input type="text" name="email" placeholder="E-mailadres" required>
            <input type="password" name="password" placeholder="Wachtwoord" required>
            <input type="password" name="password_repeat" placeholder="Herhaal wachtwoord" required>
            
            <input type="text" name="school" placeholder="School" required>
            
            <select name="niveau" required>
                <option value="">Kies niveau</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>

            <input type="text" name="vak" placeholder="Vak" required>

            <button type="submit">Account aanmaken</button>
        </form>
    </div>
</div>

<button onclick="document.getElementById('registerModal').style.display='block'">
    Nieuwe account aanmaken
</button>

<script>
// Sluit modal als je buiten het venster klikt
window.onclick = function(event) {
    let modal = document.getElementById("registerModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
}
</script>
<?php
session_start();

// DATABASEVERBINDING
$con = mysqli_connect("localhost", "root", "", "po_webapp");
if (!$con) { die("Databasefout: " . mysqli_connect_error()); }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_repeat = $_POST['password_repeat'];
    $school = $_POST['school'];
    $niveau = $_POST['niveau'];
    $vak = $_POST['vak'];

    // Wachtwoorden controleren
    if ($password !== $password_repeat) {
        die("❌ Wachtwoorden komen niet overeen");
    }

    // E-mailadres controleren of al bestaat
    $stmt = mysqli_prepare($con, "SELECT id FROM gebruiker WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
        die("❌ E-mailadres bestaat al");
    }

    // Wachtwoord veilig opslaan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($con, "INSERT INTO gebruiker (voornaam, achternaam, email, wachtwoord, school, niveau, vak) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssssis", $first_name, $last_name, $email, $hashed_password, $school, $niveau, $vak);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "✅ Account succesvol aangemaakt!";
    } else {
        echo "❌ Fout bij het aanmaken van account: " . mysqli_error($con);
    }
}
?>

</body>
</html>

