<?php

namespace App\Controller;

use App\Service\BillService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/bill/{id}', name: 'app_bill_update', methods: ["POST"])]
    public function updateBill(Request $request, BillService $billService, $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $bill = $billService->createOrUpdate($data,$id);
        return $this->json(["bill"=>$bill]);
    }

    #[Route('/bill/{id}', name: 'app_bill_delete', methods: ["DELETE"])]
    public function deleteBill(BillService $billService, $id): JsonResponse
    {
        $bill = $billService->deleteBill($id);
        return $this->json(["bill"=>$bill]);
    }

    #[Route('/bill', name: 'app_bill_get_all', methods: ["GET"])]
    public function getBills(BillService $billService): JsonResponse
    {
        $bills = $billService->getBills();
        return $this->json(["bill"=>$bills]);
    }

    #[Route('/bill/{id}', name: 'app_bill_create', methods: ["POST"])]
    public function deleteBillById(BillService $billService, $id): JsonResponse
    {
        $bill = $billService->getBillById($id);
        return $this->json(["bill"=>$bill]);
    }
}
