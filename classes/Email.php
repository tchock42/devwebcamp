<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

class Email {

    public $email;
    public $nombre;
    public $token;
    
    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {

        try {
            // create a new object
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASS'];
            $mail->Port = $_ENV['EMAIL_PORT'];
        
            $mail->setFrom('cuentas@devwebcamp.com');
            $mail->addAddress($this->email, $this->nombre);
            $mail->Subject = 'Confirma tu Cuenta';

            // Set HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            $contenido = '<html>';
            $contenido .= "<p><strong>Hola " . $this->nombre .  "</strong> Has Registrado Correctamente tu cuenta en DevWebCamp; pero es necesario confirmarla</p>";
            $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['APP_URL'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a>";       
            $contenido .= "<p>Si tu no creaste esta cuenta; puedes ignorar el mensaje</p>";
            $contenido .= '</html>';
            $mail->Body = $contenido;

            //Enviar el mail
            if($mail->send()) {
                echo 'El correo ha sido enviado correctamente.';
            } else {
                echo 'Hubo un error al enviar el correo.';
            }
        } catch (Exception $e) {
            echo "Hubo un error al enviar el correo: {$mail->ErrorInfo}";
        }

    }

    public function enviarInstrucciones() {

        try {
            // create a new object
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASS'];
            $mail->Port = $_ENV['EMAIL_PORT'];
        
            $mail->setFrom('cuentas@devwebcamp.com');
            $mail->addAddress($this->email, $this->nombre);
            $mail->Subject = 'Reestablece tu password';

            // Set HTML
            $mail->isHTML(TRUE);
            $mail->CharSet = 'UTF-8';

            $contenido = '<html>';
            $contenido .= "<p><strong>Hola " . $this->nombre .  "</strong> Has solicitado reestablecer tu password, sigue el siguiente enlace para hacerlo.</p>";
            $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['APP_URL'] . "/reestablecer?token=" . $this->token . "'>Reestablecer Password</a>";        
            $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
            $contenido .= '</html>';
            $mail->Body = $contenido;

            //Enviar el mail
            if($mail->send()) {
                echo 'El correo ha sido enviado correctamente.';
            } else {
                echo 'Hubo un error al enviar el correo.';
            }
        } catch (Exception $e) {
            echo "Hubo un error al enviar el correo: {$mail->ErrorInfo}";
        }

    }
}