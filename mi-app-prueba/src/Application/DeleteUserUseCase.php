<?php
namespace App\Application;

use App\Domain\UserRepositoryInterface;

class DeleteUserUseCase
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function execute(int $id): bool
    {
        // Aquí también podrías verificar si el usuario existe antes de borrar
        return $this->userRepository->delete($id);
    }
}