<?php
namespace App\Application;

use App\Domain\User;
use App\Domain\UserRepositoryInterface;

class UpdateUserUseCase
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function execute(int $id, string $name, string $email): User
    {
        // En una aplicación real, primero verificarías que el usuario existe
        $user = new User($id, $name, $email);
        return $this->userRepository->save($user);
    }
}