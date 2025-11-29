<?php
session_start();

// Check of ingelogd
if (!isset($_SESSION['username'])) die("Je moet eerst inloggen!");

$con = mysqli_connect("localhost","root","","po_webapp");
if (!$con) die("Databasefout: " . mysqli_connect_error());

// Haal niveau uit URL (verwacht: HAVO of VWO, consistent met ENUM)
$niveau = $_GET['niveau'] ?? null;
$username = $_SESSION['username'];

// Update niveau in de tabel 'gebruiker'
$stmt = mysqli_prepare($con, "UPDATE gebruiker SET niveau=? WHERE gebruikersnaam=?");
mysqli_stmt_bind_param($stmt, "ss", $niveau, $username);
mysqli_stmt_execute($stmt);

// Update sessie
$_SESSION['niveau'] = $niveau;

header("Location: index.php");
exit();
?>