document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("loginForm");

  loginForm.addEventListener("submit", function (event) {
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    // Fehler-Array für Benutzerfreundlichkeit
    let errors = [];

    // Benutzername validieren
    if (email.length < 5 || !email.includes("@")) {
      errors.push(
        "Der Benutzername muss mindestens 5 Zeichen lang sein und ein '@' enthalten."
      );
    }

    // Passwort validieren
    const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{9,}$/;
    if (!passwordRegex.test(password)) {
      errors.push(
        "Das Passwort muss mindestens 9 Zeichen lang sein und Großbuchstaben, Kleinbuchstaben und eine Zahl enthalten."
      );
    }

    // Wenn Fehler vorliegen, Formular nicht absenden
    if (errors.length > 0) {
      event.preventDefault();
      alert(errors.join("\n"));
    }
  });
});
