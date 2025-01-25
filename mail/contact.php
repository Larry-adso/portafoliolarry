<?php

require '../php/nomina/vendor/autoload.php';

header('Content-Type: application/json');

// Validar los datos enviados
if (empty($_POST['name']) || empty($_POST['subject']) || empty($_POST['message']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400); // Código 400: Solicitud incorrecta
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos o inválidos.']);
    exit();
}

// Sanitizar los datos recibidos
$name = strip_tags(htmlspecialchars($_POST['name']));
$email = strip_tags(htmlspecialchars($_POST['email']));
$subject = strip_tags(htmlspecialchars($_POST['subject']));
$message = strip_tags(htmlspecialchars($_POST['message']));

// Preparar el mensaje para WhatsApp
$mensajito = "Hola Larry! " . $name . " quiere hablar contigo. Su correo es " . $email . ", su número de WhatsApp es " . $subject . ", y su mensaje es: *" . $message . "*";

$dotenv = Dotenv\Dotenv::createImmutable('../php/nomina/dev/PHP/');
$dotenv->load();


// Configuración de la API de Facebook para enviar mensajes de WhatsApp
$token = $_ENV['API_TOKEN'];
$telefono = $_ENV['NUMERO'];
$url = $_ENV['API_URL'];

// Configuración del cuerpo del mensaje
$mensaje = json_encode([
    'messaging_product' => 'whatsapp',
    'to' => $telefono,
    'type' => 'text',
    'text' => [
        'body' => $mensajito
    ]
]);

// Cabeceras para la solicitud HTTP
$header = [
    "Authorization: Bearer " . $token,
    "Content-Type: application/json",
];

// Inicializar cURL
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POSTFIELDS, $mensaje);
curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Ejecutar la solicitud y obtener la respuesta
$response = curl_exec($curl);
$status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// Manejar errores en la solicitud cURL
if (curl_errno($curl)) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al intentar enviar el mensaje. Detalles: ' . curl_error($curl)
    ]);
    curl_close($curl);
    exit();
}

// Cerrar cURL
curl_close($curl);

// Analizar la respuesta de la API
$responseData = json_decode($response, true);

// Manejar la respuesta según el código de estado
if ($status_code >= 200 && $status_code < 300) {
    // Envío exitoso
    echo json_encode([
        'status' => 'success',
        'message' => 'Tu mensaje fue enviado correctamente. ¡Gracias por contactarnos!',
        'response' => $responseData // Opcional: incluye la respuesta de la API
    ]);
} else {
    // Error en la API de WhatsApp
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Hubo un problema al enviar el mensaje. Intenta nuevamente.',
        'response' => $responseData // Opcional: incluye la respuesta de la API para depuración
    ]);
}
?>
