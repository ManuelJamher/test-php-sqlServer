<?php
// --- DECLARACIONES GLOBALES OBLIGATORIAS ---
require __DIR__ . '/../vendor/autoload.php';

// Importamos TODAS las clases que vamos a necesitar para el CRUD completo
use App\Application\CreateUserUseCase;
use App\Application\DeleteUserUseCase;
use App\Application\FindAllUsersUseCase;
use App\Application\UpdateUserUseCase;
use App\Infrastructure\SqlServerUserRepository;
use App\Domain\User;
// --- FIN DE LAS DECLARACIONES GLOBALES ---

// --- Lógica de Router y API ---
$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Verificamos si la petición es para nuestra API
if (strpos($requestUri, '/api/users.php') === 0) {
    
    // Establecer cabeceras comunes
    header("Content-Type: application/json");
    
    // Inyección de Dependencias Simple
    $repository = new SqlServerUserRepository();
    
    try {
        switch ($method) {
            case 'GET':
                $findAllUsersUseCase = new FindAllUsersUseCase($repository);
                $users = $findAllUsersUseCase->execute();
                echo json_encode($users);
                break;

            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
                if (empty($data['name']) || empty($data['email'])) {
                    throw new InvalidArgumentException("Nombre y email son requeridos.");
                }
                $createUserUseCase = new CreateUserUseCase($repository);
                $newUser = $createUserUseCase->execute($data['name'], $data['email']);
                http_response_code(201); // 201 Created
                echo json_encode($newUser);
                break;

            case 'PUT':
                $data = json_decode(file_get_contents('php://input'), true);
                if (empty($data['id']) || empty($data['name']) || empty($data['email'])) {
                    throw new InvalidArgumentException("ID, nombre y email son requeridos para actualizar.");
                }
                $updateUserUseCase = new UpdateUserUseCase($repository);
                $updatedUser = $updateUserUseCase->execute((int)$data['id'], $data['name'], $data['email']);
                echo json_encode($updatedUser);
                break;

            case 'DELETE':
                // Extraemos el ID de los parámetros de la URL (?id=X)
                parse_str($_SERVER['QUERY_STRING'], $queryParams);
                $id = (int)($queryParams['id'] ?? 0);
                if ($id <= 0) {
                    throw new InvalidArgumentException("ID de usuario no válido.");
                }
                $deleteUserUseCase = new DeleteUserUseCase($repository);
                $deleteUserUseCase->execute($id);
                echo json_encode(['success' => true]);
                break;
            
            default:
                http_response_code(405); // Method Not Allowed
                echo json_encode(['error' => 'Método no permitido']);
                break;
        }

    } catch (Exception $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Ocurrió un error en el servidor: ' . $e->getMessage()]);
    }
    
    // Detener el script aquí para no enviar el HTML.
    exit;
}
// --- Fin de la Lógica de Router y API ---

// Si no fue una petición a la API, se muestra el HTML
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