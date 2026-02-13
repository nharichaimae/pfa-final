<?php

namespace App\Repository\auth;

use App\Entity\user\Utilisateur;
use App\DTO\auth\LoginResponseDTO;
use App\DTO\auth\UserResponseDTO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRepository extends ServiceEntityRepository implements IUser
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }
// UserRepository.php
public function login(string $email, string $password, UserPasswordHasherInterface $passwordHasher): ?Utilisateur
{
    $user = $this->findOneBy(['email' => $email]);

    if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
        return null;
    }

    return $user; // ✅ retourne l'entité, pas le DTO
}

}

