<?php

namespace App\Repository\auth;

use App\Entity\user\Utilisateur;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

interface IUser
{

    public function login(
        string $email,
        string $password,
        UserPasswordHasherInterface $passwordHasher
    ): ?Utilisateur;
}
