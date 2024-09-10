document.addEventListener('DOMContentLoaded', function() {
    // Récupére l'élément input de la date
    var dateInput = document.getElementsByName('date')[0];

    // Obtenir la date actuelle au format YYYY-MM-DD
    var currentDate = new Date().toISOString().split('T')[0];

    // Rempli automatiquement l'input de la date avec la date actuelle
    dateInput.value = currentDate;

    // Récupére les éléments du formulaire
    var emailInput = document.getElementsByName('email')[0];
    var emailError = document.getElementById('email-error');

    var nomInput = document.getElementsByName('nom')[0];
    var nomError = document.getElementById('nom-error');

    var prenomInput = document.getElementsByName('prenom')[0];
    var prenomError = document.getElementById('prenom-error');

    var genderDetails = document.querySelector('.gender-details');
    var genderError = document.getElementById('gender-error');
    var genderInputs = document.querySelectorAll('input[name="gender"]');
    var errorDisplay = document.getElementById('gender-error-display');

    var submitButton = document.querySelector('input[type="submit"]');
    var form = document.querySelector('form');

    // Initialise la variable de validation à true
    var isValid = true;

// Fonction de validation de l'email
function validateEmail() {
    var email = emailInput.value.trim();
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email === '' || !emailRegex.test(email)) {
        emailError.innerText = 'Veuillez entrer une adresse email valide.';
        emailInput.style.border = '2px solid red';
        isValid = false;
    } else {
        emailError.innerText = '';
        emailInput.style.border = '2px solid green';
    }
}

// Fonction de validation du nom
function validateNom() {
    var nom = nomInput.value.trim();
    var nomRegex = /^[a-zA-ZÀ-ÿ\s-]+$/; // Autorise les lettres, les espaces, et le tiret (-)

    if (nom === '' || !nomRegex.test(nom)) {
        nomError.innerText = 'Le nom ne doit contenir que des lettres, des espaces et le tiret (-).';
        nomInput.style.border = '2px solid red';
        isValid = false;
    } else {
        nomError.innerText = '';
        nomInput.style.border = '2px solid green';
    }
}

// Fonction de validation du prénom
function validatePrenom() {
    var prenom = prenomInput.value.trim();
    var prenomRegex = /^[a-zA-ZÀ-ÿ\s-]+$/; // Autorise les lettres, les espaces, et le tiret (-)

    if (prenom === '' || !prenomRegex.test(prenom)) {
        prenomError.innerText = 'Le prénom ne doit contenir que des lettres, des espaces et le tiret (-).';
        prenomInput.style.border = '2px solid red';
        isValid = false;
    } else {
        prenomError.innerText = '';
        prenomInput.style.border = '2px solid green';
    }
}

    // Fonction de validation du genre
    function validateGender() {
        var selectedGender = Array.from(genderInputs).some(input => input.checked);

        if (!selectedGender) {
            genderError.innerText = 'Veuillez sélectionner votre genre.';
            genderDetails.style.border = '2px solid red';
            isValid = false;
        } else {
            genderError.innerText = '';
            genderDetails.style.border = '2px solid green';
        }
    }


// Fonction de validation de la profession
function validateProfession() {
    var selectedProfession = professionSelect.value;

    if (selectedProfession === '' || selectedProfession ==='Quel est votre profession ?' ) {
        professionError.innerText = 'Veuillez sélectionner votre profession.';
        professionSelect.style.border = '2px solid red';
        submitButton.disabled = true;
        return false;
    } else {
        professionError.innerText = '';
        professionSelect.style.border = '2px solid green';
        submitButton.disabled = false;
        return true;
    }
}


    // Fonction de validation du sujet
    function validateSujet() {
        var sujet = sujetInput.value.trim();

        if (sujet === '') {
            sujetError.innerText = 'Veuillez entrer le sujet de votre demande de contact.';
            sujetInput.style.border = '2px solid red';
            submitButton.disabled = true;
            return false;
        } else {
            sujetError.innerText = '';
            sujetInput.style.border = '2px solid green';
            submitButton.disabled = false;
            return true;
        }
    }

    // Fonction de validation du message
    function validateMessage() {
        var message = messageTextarea.value.trim();

        if (message === '') {
            messageError.innerText = 'Veuillez écrire votre message.';
            messageTextarea.style.border = '2px solid red';
            submitButton.disabled = true;
            return false;
        } else {
            messageError.innerText = '';
            messageTextarea.style.border = '2px solid green';
            submitButton.disabled = false;
            return true;
        }
    }

    // Récupére l'élément select de la profession
    var professionSelect = document.getElementsByName('profession')[0];
    var professionError = document.getElementById('profession-error');

    // Affiche la première option (supposons que la première option a l'indice 0)
    professionSelect.options[0].style.display = '';

    // Ajoute un écouteur d'événement pour le changement de la profession
    professionSelect.addEventListener('change', validateProfession);

    // Réinitialise la bordure et le message d'erreur lorsqu'on clique sur le select
    professionSelect.addEventListener('focus', function() {
        professionSelect.style.border = '';
        professionError.innerText = '';
    });

    // Ajoute des écouteurs d'événements
    emailInput.addEventListener('blur', validateEmail);
    nomInput.addEventListener('blur', validateNom);
    prenomInput.addEventListener('blur', validatePrenom);

    // Ajoute des écouteurs d'événements pour chaque input de genre
    genderInputs.forEach(function(input) {
        input.addEventListener('change', validateGender);
    });

    // Récupére l'élément select de la profession
    var sujetInput = document.getElementsByName('sujet')[0];
    var sujetError = document.getElementById('sujet-error');

    // Défini la valeur de l'input comme une chaîne vide au chargement de la page
    sujetInput.value = '';

    // Ajoute un écouteur d'événement pour la perte de focus sur le sujet
    sujetInput.addEventListener('blur', validateSujet);

    // Réinitialise la bordure et le message d'erreur lorsqu'on clique sur le sujet
    sujetInput.addEventListener('focus', function() {
        sujetInput.style.border = '';
        sujetError.innerText = '';
    });

    // Récupére l'élément textarea du message
    var messageTextarea = document.getElementsByName('textarea')[0];
    var messageError = document.getElementById('message-error');

    // Défini la valeur de l'input comme une chaîne vide au chargement de la page
    messageTextarea.value = '';

    // Ajoute un écouteur d'événement pour la perte de focus sur le message
    messageTextarea.addEventListener('blur', validateMessage);

    // Réinitialise la bordure et le message d'erreur lorsqu'on clique sur le message
    messageTextarea.addEventListener('focus', function() {
        messageTextarea.style.border = '';
        messageError.innerText = '';
    });

    // Ajoute un écouteur d'événement pour le formulaire
    form.addEventListener('submit', function(event) {
        // Réinitialise la variable de validation à true avant chaque soumission
        isValid = true;

        // Valide chaque champ
        validateEmail();
        validateNom();
        validatePrenom();
        validateGender();
        validateProfession();
        validateSujet();
        validateMessage();

        // Si l'une des validations échoue, annule l'envoi du formulaire
        if (!isValid) {
            event.preventDefault();
        }
    });

});
