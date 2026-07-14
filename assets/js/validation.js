// Wartet bis das HTML komplett geladen ist
document.addEventListener('DOMContentLoaded', function () {

    //  Validierung für Registrierungsformular 
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            let isValid = true;

            const username = document.getElementById('username');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirm');

            // Fehlermeldungen zurücksetzen (DOM-Manipulation)
            clearError('usernameError');
            clearError('emailError');
            clearError('passwordError');
            clearError('passwordConfirmError');

            // Benutzername prüfen
            if (username.value.trim().length < 3) {
                showError('usernameError', 'Benutzername muss mindestens 3 Zeichen lang sein.');
                isValid = false;
            }

            // E-Mail prüfen (einfache Regex)
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email.value.trim())) {
                showError('emailError', 'Bitte eine gültige E-Mail-Adresse eingeben.');
                isValid = false;
            }

            // Passwort prüfen
            if (password.value.length < 8) {
                showError('passwordError', 'Passwort muss mindestens 8 Zeichen lang sein.');
                isValid = false;
            }

            // Passwort-Bestätigung prüfen
            if (password.value !== passwordConfirm.value) {
                showError('passwordConfirmError', 'Passwörter stimmen nicht überein.');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault(); 
            }
        });
    }

    //  Validierung für Login-Formular 
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            const username = document.getElementById('username');
            const password = document.getElementById('password');

            if (username.value.trim() === '' || password.value === '') {
                e.preventDefault();
                alert('Bitte Benutzername und Passwort ausfüllen.');
            }
        });
    }

    //  Validierung für Dashboard-Eintragsformular 
    const entryForm = document.getElementById('entryForm');
    if (entryForm) {
        entryForm.addEventListener('submit', function (e) {
            const title = document.getElementById('title');
            clearError('titleError');

            if (title.value.trim().length < 2) {
                showError('titleError', 'Titel muss mindestens 2 Zeichen lang sein.');
                e.preventDefault();
            }
        });
    }

    //  Hilfsfunktionen für DOM-Manipulation 
    function showError(elementId, message) {
        const el = document.getElementById(elementId);
        if (el) {
            el.textContent = message;
        }
    }

    function clearError(elementId) {
        const el = document.getElementById(elementId);
        if (el) {
            el.textContent = '';
        }
    }
});