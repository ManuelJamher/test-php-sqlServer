<?php
namespace App\Application;

use App\Domain\User;
use App\Domain\UserRepositoryInterface;

class CreateUserUseCase
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function execute(string $name, string $email): User
    {
        $user = new User(null, $name, $email);
        return $this->userRepository->save($user);
    }
}