<?php

namespace App\Controller;

use App\Service\BillService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BillController extends AbstractController
{
    #[Route('/bill', name: 'app_bill_create', methods: ["POST"])]
    public function createBill(Request $request, BillService $billService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $bill = $billService->createOrUpdate($data);
        return $this->json(["bill"=>$bill]);
    }
}
