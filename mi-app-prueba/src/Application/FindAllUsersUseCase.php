<?php
namespace App\Application;

use App\Domain\UserRepositoryInterface;

class FindAllUsersUseCase
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function execute(): array
    {
        return $this->userRepository->findAll();
    }
}