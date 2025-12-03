document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            clearErrors();
            
            if (validateForm()) {
                form.submit();
            }
        });

        usernameInput.addEventListener('blur', function() {
            validateUsername();
        });

        usernameInput.addEventListener('focus', function() {
            clearFieldError('username');
        });

        passwordInput.addEventListener('blur', function() {
            validatePassword();
        });

        passwordInput.addEventListener('focus', function() {
            clearFieldError('password');
        });
    }
});

function validateForm() {
    let isValid = true;

    if (!validateUsername()) {
        isValid = false;
    }

    if (!validatePassword()) {
        isValid = false;
    }

    return isValid;
}

function validateUsername() {
    const username = document.getElementById('username').value.trim();
    const errorElement = document.getElementById('username-error');

    if (!username) {
        showError('username', 'Gebruikersnaam is verplicht.');
        return false;
    }

    if (username.length < 3) {
        showError('username', 'Gebruikersnaam moet minimaal 3 karakters lang zijn.');
        return false;
    }

    if (username.length > 50) {
        showError('username', 'Gebruikersnaam mag maximaal 50 karakters lang zijn.');
        return false;
    }

    return true;
}

function validatePassword() {
    const password = document.getElementById('password').value;
    const errorElement = document.getElementById('password-error');

    if (!password) {
        showError('password', 'Wachtwoord is verplicht.');
        return false;
    }

    if (password.length < 6) {
        showError('password', 'Wachtwoord moet minimaal 6 karakters lang zijn.');
        return false;
    }

    if (password.length > 255) {
        showError('password', 'Wachtwoord mag maximaal 255 karakters lang zijn.');
        return false;
    }

    return true;
}

function showError(fieldId, message) {
    const errorElement = document.getElementById(fieldId + '-error');
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}

function clearFieldError(fieldId) {
    const errorElement = document.getElementById(fieldId + '-error');
    if (errorElement) {
        errorElement.textContent = '';
        errorElement.style.display = 'none';
    }
}

function clearErrors() {
    clearFieldError('username');
    clearFieldError('password');
}
