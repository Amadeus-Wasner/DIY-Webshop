<?php

return [
    'driver' => 'smtp', // Standardtreiber für Mail
    'host' => 'smtp.gmail.com', // Gmail SMTP-Host
    'port' => 587, // Gmail SMTP-Port
    'encryption' => 'tls', // Verschlüsselung
    'username' => getenv('MAIL_USERNAME') ?: 'your-email@gmail.com', // Fallback, falls keine Umgebungsvariable
    'password' => getenv('MAIL_PASSWORD') ?: 'your-password', // Fallback für Passwort
    'from' => [
        'address' => getenv('MAIL_FROM_ADDRESS') ?: 'your-email@gmail.com', // Fallback für Absenderadresse
        'name' => getenv('MAIL_FROM_NAME') ?: 'Webshop Team', // Anzeigename für E-Mail
    ],
];
