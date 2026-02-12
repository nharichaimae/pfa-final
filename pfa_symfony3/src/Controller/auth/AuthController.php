<?php

namespace App\Controller;
use App\Repository\auth\IUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthController extends AbstractController
{
       private $jwtEncoder;

    public function __construct(\Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface $jwtEncoder)
    {
        $this->jwtEncoder = $jwtEncoder;
    }
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(
        Request $request,
        IUser $userRepository,
        JWTTokenManagerInterface $jwtManager,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['email'], $data['password'])) {
            return new JsonResponse(['message' => 'Email et mot de passe requis'], 400);
        }

        $loginDTO = $userRepository->login($data['email'], $data['password'], $passwordHasher);

        if (!$loginDTO) {
            return new JsonResponse([
                'authenticated' => false,
                'message' => 'Identifiants invalides'
            ], 401);
        }
        $user = $userRepository->find($loginDTO->id);
        $loginDTO->token = $jwtManager->create($user);

        return new JsonResponse([
            'authenticated' => $loginDTO->authenticated,
            'token' => $loginDTO->token,
            'id' => $loginDTO->id,
            'email' => $loginDTO->email,
            'role' => $loginDTO->role  
        ]);
    }
}
