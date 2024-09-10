<?php

session_start();

require_once("getID.php");

// Vérification si l'utilisateur est connecté via les cookies
if (!isset($_COOKIE["user_email"]) || !isset($_COOKIE["user_motdepasse"]) || !isset($_COOKIE["user_pseudonyme"])) {
    // Rediriger l'utilisateur vers la page de connexion
    header("Location: connexion.php");
    exit(); // Assurez-vous d'arrêter l'exécution du script après la redirection
}

// Vérifie si les données du formulaire sont envoyées
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $voiture_id = $_POST["voiture_id"];
    $voiture_nom = $_POST["voiture_nom"];
    $voiture_prix = $_POST["voiture_prix"];

    // Récupére l'ID de l'utilisateur connecté
    $user_id = obtenir_id_utilisateur_depuis_base_de_donnees($_COOKIE["user_email"], $_COOKIE["user_motdepasse"]);

    if ($user_id !== null) {
        // Établi une connexion à la base de données
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "DriveElite";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Vérifie la quantité disponible en stock pour le produit spécifié
            $sqlStock = "SELECT quantite FROM Produits WHERE id = :produit_id";
            $stmtStock = $conn->prepare($sqlStock);
            $stmtStock->bindParam(':produit_id', $voiture_id);
            $stmtStock->execute();
            $rowStock = $stmtStock->fetch(PDO::FETCH_ASSOC);
            $stockDisponible = $rowStock['quantite'];

            // Vérifie si la quantité demandée est disponible en stock
            if ($stockDisponible >= 1) { // On vérifie simplement si le stock est supérieur ou égal à 1
                // Insérer les données dans la table PanierElements avec l'ID de l'utilisateur
                $sql = "INSERT INTO PanierElements (produit_id, quantite, client) VALUES (:produit_id, 1, :client)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':produit_id', $voiture_id);
                $stmt->bindParam(':client', $user_id);
                $stmt->execute();

                // Met à jour le stock du produit
                $nouveauStock = $stockDisponible - 1; // Soustraire 1 à la quantité en stock
                $sqlUpdateStock = "UPDATE Produits SET quantite = :nouveau_stock WHERE id = :produit_id";
                $stmtUpdateStock = $conn->prepare($sqlUpdateStock);
                $stmtUpdateStock->bindParam(':nouveau_stock', $nouveauStock);
                $stmtUpdateStock->bindParam(':produit_id', $voiture_id);
                $stmtUpdateStock->execute();

                // Affiche un message de succès à l'utilisateur
                echo "Produit ajouté au panier avec succès.";
            } else {
                // Affiche un message d'erreur si la quantité demandée n'est pas disponible en stock
                echo "Erreur : La quantité demandée n'est pas disponible en stock.";
            }

        } catch(PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }

        $conn = null;
    } else {
        // Gérer le cas où l'identifiant de l'utilisateur n'a pas pu être récupéré
        echo "Erreur : Impossible de récupérer l'identifiant de l'utilisateur.";
    }

}
?>
