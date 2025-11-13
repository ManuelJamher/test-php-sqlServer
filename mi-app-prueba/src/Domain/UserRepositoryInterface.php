<?php
namespace App\Domain;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function findAll(): array;
    public function save(User $user): User; // Para crear y actualizar
    public function delete(int $id): bool;
}