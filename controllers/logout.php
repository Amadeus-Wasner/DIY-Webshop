<?php
// Session starten und zerstören
session_start();
session_destroy();

// Zurück zur Login-Seite weiterleiten
header('Location: ../views/login_view.php');
exit;
