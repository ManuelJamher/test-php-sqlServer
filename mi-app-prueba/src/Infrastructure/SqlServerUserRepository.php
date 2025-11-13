<?php

namespace App\Infrastructure;

use App\Domain\User;
use App\Domain\UserRepositoryInterface;
use PDO;
use PDOException;

class SqlServerUserRepository implements UserRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        // Datos de conexión que creamos antes
        $host = 'localhost'; // ¡Aquí está tu hostname!
        $db = 'TestAppDB';
        $user = 'app_user';
        $pass = 'UnaContraseñaSegura123';
        $charset = 'utf8';

        $dsn = "sqlsrv:Server=$host;Database=$db";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function findById(int $id): ?User
    {
        // Para probar, vamos a simular que tenemos una tabla 'users'
        // NOTA: Esta tabla no existe, la conexión fallará si no la creas.
        // Pero el objetivo es probar que la conexión funciona.
        // En una app real, aquí harías:
        // $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
        // $stmt->execute([$id]);
        // $userData = $stmt->fetch();
        // ... y crearías el objeto User

        // Por ahora, solo confirmamos que la conexión se estableció en el constructor.
        echo "¡Conexión a la base de datos exitosa!\n";
        echo "Intentando buscar usuario con ID: $id (simulado)\n";

        // Devolvemos null porque no hay tabla real.
        return null;
    }
}