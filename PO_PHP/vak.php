<?php
session_start();
// Zorg ervoor dat de gebruiker is ingelogd
if (!isset($_SESSION['username'])) die("Je moet eerst inloggen!");

$con = mysqli_connect("localhost","root","","po_webapp");
if (!$con) die("Databasefout: " . mysqli_connect_error());

// Haal het VAK ID uit de URL
$vak_id = $_GET['vak_id'] ?? null; 
$username = $_SESSION['username'];

// Update vak_id in de tabel 'gebruiker'
$stmt = mysqli_prepare($con, "UPDATE gebruiker SET vak_id=? WHERE gebruikersnaam=?");
// 'i' is voor integer (vak_id), 's' is voor string (username)
mysqli_stmt_bind_param($stmt, "is", $vak_id, $username); 
mysqli_stmt_execute($stmt);

// Update sessie met het ID
$_SESSION['vak_id'] = $vak_id;

header("Location: index.php");
exit();
?>