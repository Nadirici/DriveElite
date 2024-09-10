<?php

require_once("data-DriveElite.php");

session_start();


function createDatabase() {
    $servername = "localhost";
    $username = "root";
    $password = ""; 

    try {
        $conn = new PDO("mysql:host=$servername", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Création de la base de données
        $sql = "CREATE DATABASE IF NOT EXISTS DriveElite";
        $conn->exec($sql);
        // echo "Base de données créée avec succès<br>";

        // Utilisation de la base de données
        $sql = "USE DriveElite";
        $conn->exec($sql);

        // Création des tables
        $sql = "CREATE TABLE IF NOT EXISTS Categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(100) UNIQUE
        )";
        $conn->exec($sql);
        // echo "Table Categories créée avec succès<br>";

        $sql = "CREATE TABLE IF NOT EXISTS Produits (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(100),
            Fichier VARCHAR(100),
            reference VARCHAR(100),
            prix DECIMAL(10, 2),
            quantite INT NOT NULL,
            categorie_id INT,

            FOREIGN KEY (categorie_id) REFERENCES Categories(id)
        )";
        $conn->exec($sql);
        // echo "Table Produits créée avec succès<br>";

        $sql = "CREATE TABLE IF NOT EXISTS Clients (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(100),
            email VARCHAR(100) UNIQUE,
            mot_de_passe VARCHAR(100)
        )";
        $conn->exec($sql);
        // echo "Table Clients créée avec succès<br>";



        $sql = "CREATE TABLE IF NOT EXISTS PanierElements (
            id INT AUTO_INCREMENT PRIMARY KEY,
            produit_id INT,
            quantite INT,
            client INT, 
            FOREIGN KEY (client) REFERENCES CLients(id),
            FOREIGN KEY (produit_id) REFERENCES Produits(id)
        )";
        $conn->exec($sql);
        // echo "Table PanierElements créée avec succès<br>";

        insertData();

        // echo "Toutes les tables ont été créées avec succès";

    } catch(PDOException $e) {
        // echo "Erreur : " . $e->getMessage();
    }

    
    $conn = null;
}

createDatabase();

