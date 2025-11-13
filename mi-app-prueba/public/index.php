<?php
// --- DECLARACIONES GLOBALES OBLIGATORIAS ---
// 1. Cargar el autoloader de Composer. DEBE ESTAR AL PRINCIPIO.
require __DIR__ . '/../vendor/autoload.php';

// 2. Importar TODAS las clases que se usarán en este archivo. DEBEN ESTAR AL PRINCIPIO, en el ámbito global.
use App\Application\FindAllUsersUseCase;
use App\Infrastructure\SqlServerUserRepository;
// --- FIN DE LAS DECLARACIONES GLOBALES ---


// --- Lógica de Router y API (AHORA SÍ, EL CÓDIGO QUE SE EJECUTA) ---
$requestUri = $_SERVER['REQUEST_URI'];

// Si la petición es EXACTAMENTE para nuestro endpoint de API...
if ($requestUri === '/api/users.php') {

    // Establecer las cabeceras para que el navegador sepa que es un JSON
    header("Content-Type: application/json");
    
    try {
        // La lógica para obtener los usuarios ya puede usar las clases importadas arriba
        $repository = new SqlServerUserRepository();
        $findAllUsersUseCase = new FindAllUsersUseCase($repository);
        $users = $findAllUsersUseCase->execute();
        
        // Imprimir los usuarios como un string JSON
        echo json_encode($users);

    } catch (Exception $e) {
        // En caso de error, enviar una respuesta de error en JSON
        http_response_code(500);
        echo json_encode(['error' => 'Ocurrió un error en el servidor: ' . $e->getMessage()]);
    }
    
    // Detener el script aquí para no enviar el HTML de abajo.
    exit; 
}
// --- Fin de la Lógica de Router y API ---


// Si la petición NO FUE para la API, el script continúa y muestra la página HTML normal.
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <h2 id="form-title">Añadir Usuario</h2>
            <form id="user-form">
                <input type="hidden" id="user-id">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-secondary" id="cancel-edit" style="display:none;">Cancelar</button>
            </form>
        </div>
        <div class="col-md-8">
            <h2>Lista de Usuarios</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="user-list">
                    <!-- Los usuarios se cargarán aquí -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="main.js"></script>
</body>
</html>