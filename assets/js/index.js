// Sobald das DOM vollständig geladen ist
document.addEventListener("DOMContentLoaded", function () {
  // Funktion zum Abrufen der Anzahl der Online-Benutzer
  const fetchOnlineUsers = () => {
    const onlineUsersCount = document.getElementById("online-users-count");
    if (!onlineUsersCount) return; // Abbrechen, wenn das Element nicht existiert

    // AJAX-Request an die PHP-Datei senden
    fetch("fetch_online_users.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP-Fehler: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        onlineUsersCount.textContent = data.online_users || "0";
      })
      .catch((error) => {
        console.error("Fehler beim Laden der Online-Benutzer:", error);
        onlineUsersCount.textContent = "Error";
      });
  };

  // Funktion wird aufgerufen
  fetchOnlineUsers();

  // Event-Listener für das Ändern des Passworts
  const changePasswordForm = document.getElementById("changePasswordForm");
  if (changePasswordForm) {
    changePasswordForm.addEventListener("submit", function (event) {
      const newPassword = document.getElementById("new_password").value;
      const confirmPassword = document.getElementById("confirm_password").value;

      // Passwörter prüfen
      if (newPassword !== confirmPassword) {
        event.preventDefault(); // Verhindert das Absenden des Formulars
        alert("Die neuen Passwörter stimmen nicht überein.");
      }
    });
  }
});
