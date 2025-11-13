<?php
// router.php - Versión FINAL y CORRECTA

$publicDir = __DIR__ . '/public';
$requestedUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Construimos la ruta completa al archivo solicitado dentro de la carpeta 'public'
$requestedFile = $publicDir . $requestedUri;

// CONDICIÓN CLAVE:
// Si el archivo solicitado existe físicamente Y NO es un directorio...
if (file_exists($requestedFile) && !is_dir($requestedFile)) {
    // ...le decimos al servidor de PHP que se detenga y sirva el archivo estático directamente.
    // Esto es para archivos como main.js, favicon.ico, o cualquier imagen.
    return false;
}

// SI NO SE CUMPLE LA CONDICIÓN DE ARRIBA:
// Para todas las demás peticiones (como la página principal '/' o la ruta de la API '/api/users.php'),
// cargamos nuestro controlador frontal 'index.php' para que las maneje.
require_once $publicDir . '/index.php';