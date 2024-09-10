<?php 



// Vérification si l'utilisateur est connecté via les cookies
if (!isset($_COOKIE["user_email"]) || !isset($_COOKIE["user_motdepasse"]) || !isset($_COOKIE["user_pseudonyme"])) {
    // Rediriger l'utilisateur vers la page de connexion
    header("Location: connexion.php");
    exit(); // Arrête l'exécution du script après la redirection
}

function obtenir_id_utilisateur_depuis_base_de_donnees($email, $mot_de_passe) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "DriveELite"; 

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prépare la requête pour sélectionner l'ID de l'utilisateur en fonction de son email et de son mot de passe
        $stmt = $conn->prepare("SELECT id FROM Clients WHERE email = :email AND mot_de_passe = :mot_de_passe");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $mot_de_passe);

        // Exécute la requête
        $stmt->execute();

        // Récupérer l'ID de l'utilisateur s'il existe
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $row['id']; // Retourner l'ID de l'utilisateur
        } else {
            return null; // Retourner null si aucun utilisateur correspondant n'est trouvé
        }

    } catch(PDOException $e) {
        // Gérer les erreurs de connexion à la base de données
        echo "Erreur : " . $e->getMessage();
        return null; // En cas d'erreur, retourner null
    }

}

