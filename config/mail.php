<?php
return [
    'host' => 'mailhog',      // nombre del contenedor
    'port' => 1025,           // puerto SMTP
    'username' => '',         // MailHog no necesita auth
    'password' => '',
    'from_email' => 'no-reply@tusitio.com',
    'from_name' => 'ServiciOs',
    'smtp_auth' => false,
    'smtp_secure' => false,   // false porque MailHog no usa TLS
];
