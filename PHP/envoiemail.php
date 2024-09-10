<?php
session_start(); // Démarre la session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $date = $_POST["date"];
    $email = $_POST["email"];
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $genre = $_POST["gender"];
    $profession = $_POST["profession"];
    $sujet = $_POST["sujet"];
    $message = $_POST["textarea"];

    // Générer la signature
    $signature = "Cordialement,\n" . $nom . " " . $prenom . "\n" . $email;

    // Adresse email côté site web pour permettre à l'utilisateur de contacter
    $from = "site@DriveElite.com";

    // Adresse email de la boîte avec les informations perso de l'utilisateur pour pouvoir le recontacter avec ses informations 
    $to = "receveur@DriveElite.com";

    // Objet de l'email
    $objet = $sujet;

    // Entête de l'email
    $headers = "From: " . $from . "\r\n";
    $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

    // Corps de l'email
    $email_body = "Information sur l'expéditeur : \n";
    $email_body .= "<---------------------------------->\n";
    $email_body .= "Date du contact : " . $date . "\n\n";
    $email_body .= "Email de l'expéditeur : " . $email . "\n\n";
    $email_body .= "Nom : " . $nom . "\n\n";
    $email_body .= "Prénom : " . $prenom . "\n\n";
    $email_body .= "Genre : " . $genre . "\n\n";
    $email_body .= "Profession : " . $profession . "\n";
    $email_body .= "<---------------------------------->\n\n";
    
    $email_body .= "Message : " . $message . "\n\n";
    $email_body .=  $signature;

    // Envoyer l'email
    if (mail($to, $objet, $email_body, $headers)) {
        // Stocker les informations dans des variables de session
        $_SESSION['email_envoye'] = true;
    }
}

// Rediriger vers la page de remerciement
header("Location: remerciement.php");
exit();


