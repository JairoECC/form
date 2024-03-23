<?php
// Requiere el autoload de Swift Mailer
require_once '../vendor/autoload.php';

// Verifica si se han enviado datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Configura el servidor SMTP de Zoho Mail
    $smtpHost = 'smtp.zoho.com';
    $smtpUsername = 'jairocalderon680@miformulario.xyz'; // Cambia por tu dirección de correo electrónico
    $smtpPassword = 'Oriaj2505@'; // Cambia por la contraseña de tu cuenta de correo electrónico
    $smtpPort = 587; // Puerto SMTP seguro para Zoho Mail

    // Recopila los datos del formulario
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Verifica si se ha enviado un archivo adjunto
    if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
        $attachment_path = $_FILES['attachment']['tmp_name'];
        $attachment_name = $_FILES['attachment']['name'];

        // Crea un Swift_Attachment con el archivo adjunto
        $attachment = new Swift_Attachment(
            file_get_contents($attachment_path),
            $attachment_name,
            mime_content_type($attachment_path)
        );
    }

    // Crea el objeto de transporte SMTP
    $transport = (new Swift_SmtpTransport($smtpHost, $smtpPort, 'tls'))
        ->setUsername($smtpUsername)
        ->setPassword($smtpPassword);

    // Crea el objeto Mailer
    $mailer = new Swift_Mailer($transport);

    // Crea el mensaje
    $swiftMessage = (new Swift_Message($subject))
        ->setFrom([$smtpUsername => $name]) // Utiliza el nombre proporcionado en el formulario como remitente
        ->setTo([$smtpUsername]) // Enviar una copia del correo al correo corporativo
        ->setBody("
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        padding: 20px;
                    }
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #FFE9D5;
                        padding: 20px;
                        border-radius: 5px;
                        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                    }
                    h1 {
                        color: #633F1D;
                        text-align: center;
                    }
                    p {
                        color: #666;
                        margin-bottom: 20px;
                    }
                    .footer {
                        margin-top: 20px;
                        text-align: center;
                        color: #999;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h1>$subject</h1>
                    <p>Saludos, $name.</p>
                    <img src='https://i.ibb.co/Cm91vDy/coffe.png' alt='Imagen de ejemplo' style='display: block; margin: 0 auto;'>
                    <p><strong>Nombre:</strong> $name</p>
                    <p><strong>Email:</strong> $email</p>
                    <p><strong>Asunto:</strong> $subject</p>
                    <p><strong>Mensaje:</strong> $message</p>
                </div>
                <div class='footer'>
                    <p>Este correo fue enviado desde tu sitio web.</p>
                </div>
            </body>
            </html>
            ", 'text/html');

    // Agrega una copia del correo al remitente
    $swiftMessage->setCc([$email => $name]); // Agrega la dirección de correo electrónico del remitente como CC

    // Adjunta el archivo al mensaje, si existe
    if(isset($attachment)) {
        $swiftMessage->attach($attachment);
    }

    // Envía el mensaje
    $result = $mailer->send($swiftMessage);

    // Verifica si el correo se envió correctamente
    if ($result) {
        echo 'success'; // Envía 'success' como respuesta si el correo se envía correctamente
    } else {
        echo 'error'; // Envía 'error' como respuesta si hay algún error al enviar el correo
    }
} else {
    // Si no se han enviado datos del formulario, redireccionar al formulario
    header("Location: index.html");
    exit();
}
?>
