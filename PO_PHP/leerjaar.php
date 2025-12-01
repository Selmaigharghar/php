<?php
session_start();

// Check of ingelogd
if (!isset($_SESSION['username'])) die("Je moet eerst inloggen!");

$con = mysqli_connect("localhost","root","","po_webapp");
if (!$con) die("Databasefout: " . mysqli_connect_error());


$leerjaar = $_GET['leerjaar'] ?? null;
$username = $_SESSION['username'];


$stmt = mysqli_prepare($con, "UPDATE gebruiker SET leerjaar=? WHERE gebruikersnaam=?");
mysqli_stmt_bind_param($stmt, "ss", $leerjaar, $username);
mysqli_stmt_execute($stmt);

// Update sessie direct
$_SESSION['leerjaar'] = $leerjaar;

header("Location: index.php");
exit();
?>