<?php

namespace App\Repository\admin;

use App\Dto\admin\AdminUpdateDto;
use App\Entity\admin\Administrateur;
use App\Mapper\admin\AdminMapper;
use Doctrine\ORM\EntityManagerInterface;

final class AdministrateurRepository implements Iadministrateur
{
    public function __construct(
        private EntityManagerInterface $em,
        private AdminMapper $mapper,
    ) {}

    public function getAdminProfile(int $id): ?array
    {
        $admin = $this->em->getRepository(Administrateur::class)->find($id);
        if (!$admin) return null;

        $dto = $this->mapper->toReadDto($admin);

        return [
            'id' => $dto->user->id,
            'email' => $dto->user->email,
            'nom' => $dto->user->nom,
            'prenom' => $dto->user->prenom,
            'role' => $dto->user->role,
        ];
    }

    public function updateAdminProfile(int $id, AdminUpdateDto $updateDto): ?array
    {
        $admin = $this->em->getRepository(Administrateur::class)->find($id);
        if (!$admin) return null;

        $this->mapper->applyUpdateDto($admin, $updateDto);
        $this->em->flush();

        $dto = $this->mapper->toReadDto($admin);

        return [
            'id' => $dto->user->id,
            'email' => $dto->user->email,
            'nom' => $dto->user->nom,
            'prenom' => $dto->user->prenom,
            'role' => $dto->user->role,
        ];
    }
}
