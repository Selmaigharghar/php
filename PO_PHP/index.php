<?php 
session_start(); 

// 1. VEILIGHEIDSCHECK: Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: Inlogscherm.php"); // Stuur naar inlogpagina
    exit();
}

$con = mysqli_connect("localhost","root","","po_webapp");

if (mysqli_connect_errno()) {
    die("Verbinding met MySQL mislukt: " . mysqli_connect_error());
}

// 2. Haal vaknaam en gebruikersdetails op voor weergave
$vak_naam = 'Nog niet gekozen';
$school = $_SESSION['school'] ?? 'Onbekend';
$leerjaar = $_SESSION['leerjaar'] ?? 'Onbekend';

if (isset($_SESSION['vak_id'])) {
    $stmt = mysqli_prepare($con, "SELECT naam FROM vak WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['vak_id']); // 'i' voor integer
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $vak_naam = $row['naam'];
    }
    mysqli_stmt_close($stmt);
}

// 3. Haal RELEVANTE quizes op voor het niveau en leerjaar van de gebruiker
$quizes = [];
$niveau = $_SESSION['niveau'] ?? null;
$leerjaar = $_SESSION['leerjaar'] ?? null;
$vak_id_filter = $_SESSION['vak_id'] ?? null; // HAALT HET GEKOZEN VAK ID OP UIT DE SESSIE

// Controleert of we alle drie de benodigde filters hebben
if ($niveau && $leerjaar && $vak_id_filter) { 

    // De SQL-query filtert nu op niveau, leerjaar EN vak ID.
    $sql = "SELECT q.titel, v.naam AS vak_naam, o.naam AS onderwerp_naam, q.id 
            FROM quiz q
            JOIN onderwerp o ON q.onderwerp_id = o.id
            JOIN vak v ON o.vak_id = v.id
            WHERE q.niveau = ? AND q.leerjaar = ? AND v.id = ?"; // HIER WORDT DE VAKFILTER TOEGEVOEGD
    
    $stmt = mysqli_prepare($con, $sql);
    // Koppel de drie parameters: twee strings (ss) en één integer (i)
    mysqli_stmt_bind_param($stmt, "ssi", $niveau, $leerjaar, $vak_id_filter);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $quizes[] = $row;
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="php.css">
        <title> BrainBoost | Home</title>
        <link rel="shortcut icon" href="quizlogo.png" type="image/x-icon">
        <style>
            .content { 
                padding: 20px; 
                max-width: 800px; 
                margin: 20px auto; 
                background: white; 
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1); 
            }
            .user-info { 
                border-bottom: 2px solid #ed7eb4; 
                padding-bottom: 15px; 
                margin-bottom: 20px; 
            }
            .quiz-list h3 { 
                color: rgb(25, 29, 153); 
                border-left: 5px solid rgb(25, 29, 153); 
                padding-left: 10px; 
            }
            .quiz-item { 
                background: #f7f7f7; 
                padding: 10px; 
                margin-bottom: 10px; 
                border-radius: 5px;
                border-left: 3px solid #ed7eb4;
            }
        </style>
    </head>
    <body>

<nav class="main">
    <ul>
        <li><a href="#">Niveau: <?= $_SESSION['niveau'] ?? "kies" ?></a>
            <ul>
                <li><a href="niveau.php?niveau=HAVO">HAVO</a></li>
                <li><a href="niveau.php?niveau=VWO">VWO</a></li>
            </ul>
        </li>
        <li><a href="#">Vak: <?= htmlspecialchars($vak_naam) ?></a>
            <ul>
                <li><a href="vak.php?vak_id=1">Wiskunde</a></li>
                <li><a href="vak.php?vak_id=2">Natuurkunde</a></li>
                <li><a href="vak.php?vak_id=3">Scheikunde</a></li>
                <li><a href="vak.php?vak_id=4">Biologie</a></li>
            </ul>
        </li>
        <li><a href="#">leerjaar: <?= $_SESSION['leerjaar'] ?? "kies" ?></a>
            <ul>
                <li><a href="leerjaar.php?leerjaar=2">2</a></li>
                <li><a href="leerjaar.php?leerjaar=3">3</a></li>
                <li><a href="leerjaar.php?leerjaar=4">4</a></li>
                <li><a href="leerjaar.php?leerjaar=5">5</a></li>
                <li><a href="leerjaar.php?leerjaar=6">6</a></li>
            </ul>
        </li>
        <li><a href="logout.php">Uitloggen</a></li>
    </ul>
</nav>

<div class="content">
    
    <div class="user-info">
        <h2>Welkom,<?= htmlspecialchars($_SESSION['username'] ?? 'Gast') ?>! </h2>
        <p>Jouw profiel:</p>
        <ul>
            <li>Niveau:<?= htmlspecialchars($niveau) ?></li>
            <li>Leerjaar: <?= htmlspecialchars($leerjaar) ?></li>
            <li>School: <?= htmlspecialchars($school) ?></li>
            <li>Voorkeursvak:<?= htmlspecialchars($vak_naam) ?></li>
        </ul>
    </div>

    <div class="quiz-list">
        <h3>Aanbevolen Quizzen voor Jouw Instellingen</h3>
        <p>We hebben quizzen voor <?= htmlspecialchars($niveau) ?> in Leerjaar <?= htmlspecialchars($leerjaar) ?> gevonden:</p>
        <?php if (!empty($quizes)): ?>
            <?php foreach ($quizes as $quiz): ?>
                <div class="quiz-item">
                    <?= htmlspecialchars($quiz['titel']) ?> (Vak: <?= htmlspecialchars($quiz['vak_naam']) ?>, Onderwerp: <?= htmlspecialchars($quiz['onderwerp_naam']) ?>)
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Er zijn momenteel geen quizzen gevonden die overeenkomen met jouw niveau en leerjaar. Probeer een ander niveau of leerjaar te kiezen in de navigatiebalk.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
