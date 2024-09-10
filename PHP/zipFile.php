<?php

// Définition des chemins des répertoires dans l'archive ZIP
$zipDirectories = array(
    'PHP' => 'PHP',
    'css' => 'css',
    'scripts' => 'scripts',
    'images' => 'images'
);

$nomRepertoire = "DriveElite";

// Chemin du répertoire racine en remontant d'un niveau supplémentaire depuis le répertoire PHP
$rootDirectory = realpath(__DIR__ . '/../'); 

$zip = new ZipArchive();
if ($zip->open($nomRepertoire . ".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {

    // Ajouter les fichiers au ZIP
    foreach ($zipDirectories as $dir => $zipDir) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootDirectory . '/' . $dir));
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getPathname();
                $relativePath = substr($filePath, strlen($rootDirectory));

                $zip->addFile($filePath, $zipDir . '/' . basename($relativePath));
            }
        }
    }

    // Ajouter le fichier index.php au ZIP (s'il existe)
    $indexFilePath = $rootDirectory . '/index.php';
    if (file_exists($indexFilePath)) {
        $zip->addFile($indexFilePath, 'index.php');
    }

    $zip->close();

    // Définir les en-têtes pour le téléchargement
    header("Content-Type: application/zip");
    header("Content-Disposition: attachment; filename=\"$nomRepertoire.zip\"");
    header("Content-Length: " . filesize($nomRepertoire . ".zip"));

    readfile($nomRepertoire . ".zip");

    unlink($nomRepertoire . ".zip");

    exit;
} else {
    echo "Erreur : Impossible de créer l'archive ZIP.";
}

?>
