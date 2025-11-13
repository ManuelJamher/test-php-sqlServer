<?php

namespace App\Application;

use App\Domain\UserRepositoryInterface;

class FindUserUseCase
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(int $userId): void
    {
        echo "Iniciando caso de uso para buscar usuario...\n";
        $user = $this->userRepository->findById($userId);

        if ($user) {
            echo "Usuario encontrado: " . $user->name . "\n";
        } else {
            echo "Usuario con ID $userId no encontrado o la tabla no existe.\n";
        }
    }
}