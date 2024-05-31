document.addEventListener("DOMContentLoaded", function() {
    var registerForm = document.getElementById("registerForm");

    registerForm.addEventListener("submit", function(event) {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirmPassword").value;

        if (password !== confirmPassword) {
            alert("As senhas não coincidem.");
            event.preventDefault(); // Impede o envio do formulário
        }
    });
});
