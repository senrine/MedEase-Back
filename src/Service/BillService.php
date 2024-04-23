<?php

namespace App\Service;

use App\Entity\Bill;
use App\Repository\BillRepository;
use App\Repository\UserRepository;
use function Symfony\Component\String\b;

class BillService
{
    private BillRepository $billRepository;
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, BillRepository $billRepository)
    {
        $this->userRepository = $userRepository;
        $this->billRepository = $billRepository;
    }

    public function createOrUpdate(array $data, int $id = null): array
    {
        $bill = $id === null ? new Bill() : $this->billRepository->findById($id);
        if(array_key_exists("patient", $data)){
            $patient = $this->userRepository->findOneById($data["patient"]);
            $bill->setPatient($patient);
        }
        if(array_key_exists("professional", $data)){
            $professional = $this->userRepository->findOneById($data["professional"]);
            $bill->setPatient($professional);
        }
        if(array_key_exists("patient", $data)){
            $bill->setPatient($data["link"]);
        }
        $this->billRepository->save($bill);

        return $bill->serialize();
    }

}