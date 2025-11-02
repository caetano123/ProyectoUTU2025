<?php
namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    protected $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../../config/mail.php';
    }

    public function send(string $to, string $name, string $subject, string $body)
    {
        $mail = new PHPMailer(true);

        try {
            // Configuración SMTP para MailHog
            $mail->isSMTP();
            $mail->Host = 'mailhog';  // nombre del servicio en docker-compose
            $mail->Port = 1025;
            $mail->SMTPAuth = false;
            $mail->SMTPSecure = false;

            // Dirección del remitente
            $mail->setFrom($this->config['from_email'], $this->config['from_name']);
            $mail->addAddress($to, $name);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            // Enviar correo
            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Error al enviar correo: {$mail->ErrorInfo}");
            return false;
        }
    }
}
