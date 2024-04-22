<?php

namespace App\Service;

use App\Repository\UserRepository;
use http\Exception\InvalidArgumentException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserService
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $hasher)
    {
        $this->userRepository = $userRepository;
        $this->hasher = $hasher;
    }

    public function fetchUsers(): array
    {
        $users = $this->userRepository->findAll();
        $serialized_users = [];

        foreach ($users as $user){
            $serialized_users[] = $user->serialize();
        }
        return $serialized_users;
    }

    public function fetchUserById(int $id) : array
    {
        return $this->userRepository->findOneById($id)->serialize();
    }

    public function signup(array $data): array
    {
        $user = new User();
        $this->validateRequestData($data);
        $user->setEmail($data["email"]);
        $user->setPassword($data["password"]);
        $user->setName($data["name"]);
        $user->setLastname($data["lastname"]);
        $user->setLocation($data["location"]);
        $user->setPhoneNumber($data["phoneNumber"]);
        $user->setPatient($data["patient"]);
        $user->setProfessional($data["professional"]);
        if ($user->isProfessional()) {
            $user->setSpeciality($data["speciality"]);
        }
        $this->userRepository->save($user);
        return $user->serialize();
    }

    private function validateRequestData(array $data): void
    {
        $requestedField = ["email", "password"];
        foreach ($requestedField as $field) {
            if (!array_key_exists($field, $data)) {
                throw new InvalidArgumentException("Invalid Request: Missing Field '$field'");
            }
        }
    }
}