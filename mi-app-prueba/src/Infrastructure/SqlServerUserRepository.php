<?php
namespace App\Infrastructure;

use App\Domain\User;
use App\Domain\UserRepositoryInterface;
use PDO;

class SqlServerUserRepository implements UserRepositoryInterface
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data['id'], $data['name'], $data['email']) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY id");
        $usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        //($usersData); // Muestra lo que devuelve la BD
        //exit; // Detiene el script aquí para que podamos ver la salida

        $users = [];
        foreach ($usersData as $data) {
            $users[] = new User($data['id'], $data['name'], $data['email']);
        }
        return $users;
    }

    public function save(User $user): User
    {
        if ($user->id) { // Actualizar
            $stmt = $this->pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->execute([$user->name, $user->email, $user->id]);
        } else { // Crear
            $stmt = $this->pdo->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
            $stmt->execute([$user->name, $user->email]);
            $user->id = (int)$this->pdo->lastInsertId();
        }
        return $user;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}