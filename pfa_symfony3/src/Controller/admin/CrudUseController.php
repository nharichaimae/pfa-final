<?php
namespace App\Controller\admin;
use App\DTO\client\ClientSearchDTO;
use App\Repository\client\Iclient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Mapper\client\ClientMapper;


class CrudUseController extends AbstractController
{
    #[Route('/clients', name: 'clients_list', methods: ['GET'])]
    public function getAllClients(Iclient $clientRepo): Response
    {
        $clientsArray = $clientRepo->getAllClients();

        return $this->json([
            'success' => true,
            'count' => count($clientsArray),
            'clients' => $clientsArray
        ]);
    }

    #[Route('/client/ajouter', name: 'client_ajouter', methods: ['POST'])]
    public function ajouter(Request $request, Iclient $clientRepo): Response
    {
        try {
            $data = $request->request->all();
            $photo = $request->files->get('photoProfil');

            if ($photo) {
                $filename = uniqid() . '.' . $photo->guessExtension();
                $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
                if (!is_writable($uploadDir)) {
                    throw new \Exception('Le dossier uploads n’est pas accessible en écriture');
                }
                $photo->move($uploadDir, $filename);
                $data['photoProfil'] = $filename;
            }

            $client = $clientRepo->ajouterClient($data);

            return $this->json([
                'message' => 'Client ajouté avec succès ✅',
                'id' => $client->getId()
            ]);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/client/{id}', name: 'client_detail', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function detail(int $id, Iclient $clientRepo): Response
    {
        $client = $clientRepo->getClientById($id);

        if (!$client) {
            return $this->json(['message' => 'Client non trouvé'], 404);
        }

        return $this->json(ClientMapper::toArray($client));
    }

    #[Route('/client/supprimer/{id}', name: 'client_supprimer', methods: ['POST','GET'], requirements: ['id' => '\d+'])]
    public function supprimer(int $id, Iclient $clientRepo): Response
    {
        $client = $clientRepo->getClientById($id);

        if (!$client) {
            return $this->json(['message' => 'Client non trouvé'], 404);
        }

        $clientRepo->supprimerClient($client);

        return $this->json([
            'message' => 'Client supprimé avec succès ✅',
        ]);
    }

    #[Route('/client/search', name: 'client_search')]
    public function search(Request $request, Iclient $clientRepo): Response
    {
        $motCle = $request->query->get('q', '');
        $dto = new ClientSearchDTO();
        $dto->motCle = $motCle;

        $clientsArray = $clientRepo->rechercheClient($dto);

        return $this->json([
            'success' => true,
            'clients' => $clientsArray
        ]);
    }
}
