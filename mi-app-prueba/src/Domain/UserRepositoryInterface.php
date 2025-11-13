<?php

namespace App\Domain;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
}