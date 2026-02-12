<?php

namespace App\Repository\auth;

use App\DTO\auth\LoginResponseDTO;
use App\DTO\auth\UserResponseDTO;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

interface IUser
{

    public function login(
        string $email,
        string $password,
        UserPasswordHasherInterface $passwordHasher
    ): ?LoginResponseDTO;
}
