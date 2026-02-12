<?php

namespace App\Controller\admin;

use App\DTO\admin\AdminUpdateDto;
use App\DTO\user\UtilisateurUpdateDto;
use App\Repository\admin\Iadministrateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/admin')]
final class AdminProfileController extends AbstractController
{
    public function __construct(
        private Iadministrateur $repo
    ) {}

    #[Route('/profile/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $admin = $this->repo->getAdminProfile($id);

        if (!$admin) {
            return $this->json(['message' => 'Admin not found'], 404);
        }

        return $this->json($admin);
    }

    #[Route('/profile/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return $this->json(['message' => 'JSON invalide'], 400);
        }

        $userUpdate = new UtilisateurUpdateDto(
            email: $data['email'] ?? null,
            nom: $data['nom'] ?? null,
            prenom: $data['prenom'] ?? null,
        );

        $adminUpdate = new AdminUpdateDto($userUpdate);

        $admin = $this->repo->updateAdminProfile($id, $adminUpdate);

        if (!$admin) {
            return $this->json(['message' => 'Admin not found'], 404);
        }

        return $this->json([
            'message' => 'Profil admin mis à jour',
            'admin' => $admin,
        ]);
    }
}
