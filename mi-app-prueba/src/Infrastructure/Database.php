<?php
namespace App\Infrastructure;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            // Cargar las variables de entorno si las estuvieras usando (lo dejamos por buena práctica)
            // $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            // $dotenv->load();

            // Usamos los valores directamente por ahora
            $host = 'localhost';
            $db   = 'TestAppDB';
            $user = 'app_user';
            $pass = 'UnaContraseñaSegura123';

            try {
                // Añadimos "TrustServerCertificate=yes" para desarrollo local.
                $dsn = "sqlsrv:Server=$host;Database=$db;TrustServerCertificate=yes";
                
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);

            } catch (PDOException $e) {
                http_response_code(500);
                die(json_encode(['error' => 'Error de conexión a la base de datos: ' . $e->getMessage()]));
            }
        }
        return self::$instance;
    }
}