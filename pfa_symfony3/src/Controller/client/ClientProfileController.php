<?php
namespace App\Controller\client;

use App\Repository\client\Iclient;
use App\DTO\client\ClientUpdateDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/client', name: 'client_')]
class ClientProfileController extends AbstractController
{
    private Iclient $clientRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(Iclient $clientRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->clientRepository = $clientRepository;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/profil/{id}', name: 'profil', methods: ['GET'])]
    public function getProfil(int $id): JsonResponse
    {
        $clientDto = $this->clientRepository->getClientProfile($id);

        if (!$clientDto) {
            return $this->json(['message' => 'Client non trouvé'], 404);
        }

        return $this->json($clientDto);
    }

    #[Route('/profil/{id}', name: 'update_profil', methods: ['PUT'])]
    public function updateProfil(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Créer le DTO de mise à jour
        $updateDto = new ClientUpdateDto(
            nom: $data['nom'] ?? null,
            prenom: $data['prenom'] ?? null,
            telephone: $data['telephone'] ?? null,
            cin: $data['cin'] ?? null,
            photoProfil: $data['photoProfil'] ?? null,
            password: $data['password'] ?? null
        );

        $success = $this->clientRepository->updateClientProfile($id, $updateDto, $this->passwordHasher);

        if (!$success) {
            return $this->json(['message' => 'Client non trouvé'], 404);
        }

        return $this->json(['message' => 'Profil mis à jour avec succès']);
    }
}
