<?php
session_start();

// Verwijder alle sessievariabelen
session_unset();    

// Vernietig de sessie
session_destroy();  

// Stuur gebruiker terug naar inlogpagina
header("Location: Inlogscherm.php"); 
exit();
?>