<?php
session_start();

// Vérification de l'authentification
if (!isset($_COOKIE['nomcookie'])) {
    header("Location: connexion.php");
    exit();
}
else{
    $redirectpage=$_SESSION["page"];
    header("Location: $redirectpage");
}

// Si l'utilisateur est authentifié, vous pouvez utiliser le cookie d'authentification pour identifier l'utilisateur
// et charger ses données à partir de la base de données ou de tout autre mécanisme d'authentification.

// Vous pouvez également définir des variables de session pour stocker des informations sur l'utilisateur si nécessaire.
?> 
