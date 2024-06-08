<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/specialty', name: 'app_user_specialty', methods: ["POST"])]
    public function getUserBySpeciality(Request $request, UserService $userService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $userService->getUsersBySpecialty($data);
        return $this->json(["user" => $user]);
    }
}
