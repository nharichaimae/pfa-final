<?php
namespace App\Repository\client;
use App\DTO\client\ClientReadDto;
use App\DTO\client\ClientUpdateDto;
use App\Mapper\client\ClientProfileMapper;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Dto\client\ClientCreateDTO;
use App\Dto\client\ClientSearchDTO;
use App\Entity\client\Client;
use App\Mapper\client\ClientMapper;
use Doctrine\ORM\EntityManagerInterface;

class ClientRepository implements Iclient
{
    private EntityManagerInterface $em;
    private ClientProfileMapper $profileMapper;

    public function __construct(EntityManagerInterface $em ,ClientProfileMapper $profileMapper )
    {
        $this->em = $em;
         $this->profileMapper = $profileMapper;
    }

    public function getAllClients(): array
    {
        $clients = $this->em->getRepository(Client::class)->findAll();
        return array_map(fn($c) => ClientMapper::toArray($c), $clients);
    }

    public function ajouterClient(array $data): Client
    {
        $dto = ClientMapper::arrayToCreateDTO($data);
        $client = ClientMapper::fromCreateDTO($dto);

        $this->em->persist($client);
        $this->em->flush();

        return $client;
    }

    public function getClientById(int $id): ?Client
    {
        return $this->em->getRepository(Client::class)->find($id);
    }

    public function supprimerClient(Client $client): void
    {
        $this->em->remove($client);
        $this->em->flush();
    }

    public function rechercheClient(ClientSearchDTO $dto): array
    {
        $result = $this->em->getRepository(Client::class)
            ->createQueryBuilder('c')
            ->where('c.nom LIKE :mot')
            ->orWhere('c.prenom LIKE :mot')
            ->orWhere('c.email LIKE :mot')
            ->orWhere('c.cin LIKE :mot')
            ->setParameter('mot', '%' . $dto->motCle . '%')
            ->getQuery()
            ->getResult();

        return array_map(fn($c) => ClientMapper::toArray($c), $result);
    }


    public function getClientProfile(int $id): ?ClientReadDto
    {
    $client = $this->em->getRepository(Client::class)->find($id);
    if (!$client) {
        return null;
    }

    return $this->profileMapper->toReadDto($client);
    }

    public function updateClientProfile(int $id, ClientUpdateDto $dto, UserPasswordHasherInterface $hasher): bool
{
    try {
        $client = $this->em->getRepository(Client::class)->find($id);
        if (!$client) {
            return false;
        }
        $this->profileMapper->applyUpdateDto($client, $dto, $hasher);
        $this->em->flush();
        return true;
    } catch (\Throwable $e) {
        throw new \Exception("Erreur lors de la mise à jour du profil: " . $e->getMessage());
    }
}

}

