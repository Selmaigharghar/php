<?php
session_start();
if (!isset($_SESSION['username'])) die("Je moet eerst inloggen!");

$con = mysqli_connect("localhost","root","","po_webapp");
if (!$con) die("Databasefout: " . mysqli_connect_error());

$vak = $_GET['vak'] ?? null;
$username = $_SESSION['username'];

// Update vak in 'gebruiker'
$stmt = mysqli_prepare($con, "UPDATE gebruiker SET vak=? WHERE gebruikersnaam=?");
mysqli_stmt_bind_param($stmt, "ss", $vak, $username);
mysqli_stmt_execute($stmt);

$_SESSION['vak'] = $vak;

header("Location: index.php");
exit();
?>