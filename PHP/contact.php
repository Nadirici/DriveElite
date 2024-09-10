<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Nadir" content="NEHILI">
    <link rel="stylesheet" type="text/css" href="../css/contact.css">
    <link rel="icon" href="../images/icon.jpg" type="image/x-icon">
    <title>Formulaire de contact</title>
</head>
<body>

<?php

session_start();

// Vérifier si l'utilisateur est connecté via les cookies
if (!isset($_COOKIE["user_email"]) || !isset($_COOKIE["user_motdepasse"]) || !isset($_COOKIE["user_pseudonyme"])) {
    // Rediriger l'utilisateur vers la page de connexion
    header("Location: connexion.php");
    exit(); // Arrête l'exécution du script après la redirection
}


// Fonction pour vérifier le genre
function verificationGenre($gender){
    $lowercaseGender = strtolower($gender);
    return ($lowercaseGender === "homme" || $lowercaseGender === "femme");
}

// Fonction pour valider le format de l'e-mail
function validerFormatEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Fonction pour valider que le nom et le prénom ne contiennent que des lettres et le tiret "-"
function validerNomPrenom($nomPrenom) {
    $nomPrenom = trim($nomPrenom);
    $nomPrenomRegex = '/^[a-zA-Z-]+$/';
    return ($nomPrenom === '' || !preg_match($nomPrenomRegex, $nomPrenom)) ? false : true;
}

// Fonction pour valider si la date est aujourd'hui
function validerDate($date) {
    $aujourdhui = date("Y-m-d");
    return ($date === $aujourdhui);
}

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    
    // Récupère les données du formulaire
    $_SESSION["date"] = $_POST["date"];
    $_SESSION["email"] = $_POST["email"];
    $_SESSION["nom"] = $_POST["nom"];
    $_SESSION["prenom"] = $_POST["prenom"];
    $_SESSION["gender"] = $_POST["gender"];
    $_SESSION["profession"]= $_POST["profession"];
    $_SESSION["sujet"] = $_POST["sujet"];
    // Convertit les sauts de ligne en balises <br> pour gérer les retours à la ligne dans le message
    $_SESSION["textarea"] = nl2br($_POST["textarea"]);

    // Valide les champs obligatoires
    $champsObligatoires = array("date", "email", "nom", "prenom", "gender", "profession", "sujet", "textarea");
    $isValid = true;

    foreach ($champsObligatoires as $champ) {
        if (empty($_POST[$champ])) {
            $isValid = false;
            break;
        }
    }

    // Validation supplémentaire
    $isValid = $isValid && verificationGenre($_POST["gender"]);
    $isValid = $isValid && validerFormatEmail($_POST["email"]);
    $isValid = $isValid && validerNomPrenom($_POST["nom"]);
    $isValid = $isValid && validerNomPrenom($_POST["prenom"]);
    $isValid = $isValid && validerDate($_POST["date"]);

    if (!$isValid) {
        // Si la validation échoue, redirige vers le formulaire de contact avec un message d'erreur
        $errorMessage = "";
        if (empty($_POST["date"])) {
            $errorMessage = "Veuillez sélectionner une date.";
        } elseif (!validerFormatEmail($_POST["email"])) {
            $errorMessage = "Veuillez saisir une adresse e-mail valide.";
        } elseif (!validerNomPrenom($_POST["nom"])) {
            $errorMessage = "Veuillez saisir un nom valide.";
        } elseif (!validerNomPrenom($_POST["prenom"])) {
            $errorMessage = "Veuillez saisir un prénom valide.";
        } elseif (!verificationGenre($_POST["gender"])) {
            $errorMessage = "Veuillez sélectionner un genre valide.";
        } elseif (!validerDate($_POST["date"])) {
            $errorMessage = "La date du contact doit être aujourd'hui.";
        } elseif (empty($_POST["profession"])) {
            $errorMessage = "Veuillez sélectionner votre profession.";
        } elseif (empty($_POST["sujet"])) {
            $errorMessage = "Veuillez saisir le sujet de votre demande de contact.";
        } elseif (empty($_POST["textarea"])) {
            $errorMessage = "Veuillez saisir votre message.";
        }
        header("Location: contact.php?error=1&message=" . urlencode($errorMessage));
        exit();
    }

    include 'envoiemail.php';

    // Si toutes les validations réussissent, redirige vers la page de remerciements
    header("Location: remerciement.php");
    exit();
}
?>

<header>    
    <div class="logo">
        <a href="../index.php"> <img class="logo" src="../images/drive_elite-modified.png" alt="logo" ></a>
    </div>
    
    <nav class="navbar">
        <ul class="nav_links">
            <li><a href="citadines.php">Citadines</a></li>
            <li><a href="SUV.php">SUV</a></li>
            <li><a href="sportives.php">Sportives</a></li>
        </ul>
    </nav>
    <div class="cta">
            <a  href="profil.php"><button>Profil</button></a>
            <a  href="panier.php"><button>Panier</button></a>
            <a  href="contact.php"><button>Contactez-nous</button></a>
    </div>
    
</header>

<div class="container">
    <!-- MENU DE GAUCHE -->
    <div class="LeftMenu">
        <div class="menu" >
            <ul>
                <div class="links">
                    <li id="menu">Menu</li>
                </div>
                <div class="links">
                    <li id="citadines"><a href="citadines.php">Citadines</a></li>
                </div>
                <div class="links">
                    <li id="SUV"><a href="SUV.php">SUV</a></li>
                </div>
                <div class="links">
                    <li id="sportive"><a href="sportives.php">Sportive</a></li>
                </div>
                <div class="links">
                    <li id="contact"><a href=contact.php>Contactez-nous</a></li>
                </div>
            </ul>
        </div>
    </div>


    

    <div class="body-form">

        <div class="formulaire-en-lui-meme">
            
        
        <?php
            // Récupérer le message d'erreur s'il existe
            $message = isset($_GET['message']) ? $_GET['message'] : "";
        ?>

            <div class="error-message">
                <?php echo $message; ?>
            </div>
            <div class="title">
                Contact
            </div>
            <form  action="contact.php" method="POST">
                <div class="user-details">

                    <div class="input-box">
                        <span class="details">Date du contact :</span> 
                        <!-- Force l'utilisateur à renseigné la date ou il demande réelement conctact -->
                        <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>"readonly>
                    </div>


                    <!-- PHP pour récupérer la date actuelle et la placer dans le champ -->
                    <?php
                    // Récupérer la date actuelle
                    $date_actuelle = date("Y-m-d");
                    // Assigner la date actuelle au champ de date
                    echo '<script>document.getElementsByName("date")[0].value = "' . $date_actuelle . '";</script>';
                    ?>

                    <div class="input-box email-box">
                        <span class="details">Votre email : </span>
                        <!-- Rempli les champs avec l'adresse email du compte pour ne pas se tromper et non modifiable -->
                        <input type="email" name="email" placeholder="Entrez votre email" value="<?php echo isset($_COOKIE['user_email']) ? $_COOKIE['user_email'] : ''; ?>" readonly>
                        <div class="error-message" id="email-error"></div>
                    </div>
                    

                    <div class="input-box nom-box">
                        <span class="details">Nom : </span>
                        <input type="text" name="nom" placeholder="Entrez votre nom">
                        <div class="error-message" id="nom-error"></div>
                    </div>

                    <div class="input-box prenom-box">
                        <span class="details">Prenom : </span>
                        <input type="text" name="prenom" placeholder="Entrez votre Prénom">
                        <div class="error-message" id="prenom-error"></div>
                    </div>

                    <div class="gender-details">
                        <input type="radio" name="gender" id="dot-1" value="Homme">
                        <input type="radio" name="gender" id="dot-2" value="Femme">
                        <span class="gender-title">Genre :</span>
                        <div class="gender-category">
                            <label for="dot-1">
                                <span class="dot one"></span>
                                <span class="gender">Homme</span>
                            </label>
                            <label for="dot-2">
                                <span class="dot two"></span>
                                <span class="gender">Femme</span>
                            </label>
                        </div>
                        <div class="error-message" id="gender-error"></div>
                    </div>

                    <div class="input-box profession-box">
                        <span class="details">Profession :</span>
                            <select name="profession">
                                <option disabled value="">Quel est votre profession ?</option>
                                <option value="Médecin">Médecin</option>
                                <option value="Avocat">Avocat</option>
                                <option value="Architecte">Architecte</option>
                                <option value="Artiste">Artiste</option>
                                <option value="Informaticien">Informaticien</option>
                                <option value="Policier">Policier</option>
                                <option value="Journaliste">Journaliste</option>
                                <option value="Agriculteur">Agriculteur</option>
                                <option value="Chef cuisinier">Chef cuisinier</option>
                                <option value="Psychologue">Psychologue</option>
                                <option value="Électricien">Électricien</option>
                                <option value="Chauffeur de taxi">Chauffeur de taxi</option>
                                <option value="Comptable">Comptable</option>
                                <option value="Mécanicien">Mécanicien</option>
                                <option value="Infirmier">Infirmier</option>
                                <option value="Plombier">Plombier</option>
                                <option value="Consultant en gestion">Consultant en gestion</option>
                                <option value="Éducateur de la petite enfance">Éducateur de la petite enfance</option>
                                <option value="Entrepreneur">Entrepreneur</option>
                                <option value="Graphiste">Graphiste</option>
                                <option value="Ingénieur">Ingénieur</option>
                        </select>

                        <div class="error-message" id="profession-error"></div>
                    </div>

                    <div class="input-box sujet-box">
                        <span class="details">Sujet de votre demande de contact : </span>
                        <input type="text" name="sujet" placeholder="Entrez le sujet ">
                        <div class="error-message" id="sujet-error"></div>
                    </div>   

                    <div class="input-box message-box">
                        <span class="details">Votre message : </span>
                        <textarea name="textarea" placeholder="Inscrivez votre message "></textarea><br>
                        <div class="error-message" id="message-error"></div>
                    </div>

                    <div class="button">
                        <input type="submit" value="Envoyez votre demande de contact" href="envoiemail.php">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<footer>
        <p>Copyright Société DriveElite<br>
            Webmaster CY Tech
        </p>

</footer>

<script src="../scripts/script.js"></script>
</body>
</html>
