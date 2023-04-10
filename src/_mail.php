<?php
namespace gamboamartin\notificaciones\mail;
use PHPMailer\PHPMailer\PHPMailer;
use stdClass;
use Throwable;

class _mail{
    final public function envia(stdClass $mensaje){
        try {

            $mail = new PHPMailer (true);
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = $mensaje->not_emisor_host;
            $mail->Port = $mensaje->not_emisor_port;
            $mail->Username = $mensaje->not_emisor_email;
            $mail->Password = $mensaje->not_emisor_password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->setFrom($mensaje->not_emisor_email, $mensaje->not_emisor_user_name);
            $mail->addAddress($mensaje->not_receptor_email, $mensaje->not_receptor_alias);
            $mail->isHTML(true);
            $mail->Subject = $mensaje->not_mensaje_asunto;
            $mail->Body = $mensaje->not_mensaje_mensaje;
            $mail->AltBody = $mensaje->not_mensaje_mensaje;
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->send();


        } catch (Throwable $e) {
            echo "Mailer Error: ".$e;
            exit;
        }
        return $mail;
    }
}
