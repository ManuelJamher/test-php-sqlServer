<?php

// Autoloader simple para no usar Composer en este ejemplo básico
spl_autoload_register(function ($class) {
    // Proyect-specific namespace prefix
    $prefix = 'App\\';
    // Base directory for the namespace prefix
    $base_dir = __DIR__ . '/src/';
    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Application\FindUserUseCase;
use App\Infrastructure\SqlServerUserRepository;

echo "--- Prueba de Conexión a SQL Server con Clean Architecture ---\n\n";

try {
    // 1. (Capa de Infraestructura) Creamos la implementación concreta del repositorio.
    //    Esto dispara la conexión a la base de datos.
    $userRepository = new SqlServerUserRepository();

    // 2. (Capa de Aplicación) Creamos el caso de uso, inyectándole la dependencia.
    $findUserUseCase = new FindUserUseCase($userRepository);

    // 3. (Capa de Presentación/Entrada) Ejecutamos el caso de uso.
    $findUserUseCase->execute(1);

} catch (PDOException $e) {
    echo "ERROR DE CONEXIÓN: " . $e->getMessage() . "\n";
    echo "Verifica que:\n";
    echo "1. El contenedor Docker 'sqlserver_dev' esté corriendo.\n";
    echo "2. El hostname, base de datos, usuario y contraseña en SqlServerUserRepository.php sean correctos.\n";
    echo "3. Las extensiones pdo_sqlsrv y sqlsrv de PHP estén instaladas y activas.\n";
}