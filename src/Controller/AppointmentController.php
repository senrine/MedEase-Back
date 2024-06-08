<?php

namespace App\Controller;

use App\Service\AppointmentService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppointmentController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/appointment', name: 'app_appointment_create', methods: ["POST"])]
    public function createAppointment(Request $request, AppointmentService $appointmentService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $appointment = $appointmentService->createAppointment($data);

        return $this->json(["appointment" => $appointment]);
    }

    #[Route('/appointment', name: 'app_appointment_get', methods: ["GET"])]
    public function fetchAppointments(AppointmentService $appointmentService): JsonResponse
    {
        $appointments = $appointmentService->fetchAppointments();

        return $this->json(["appointment" => $appointments]);
    }


    #[Route('/appointment/{id}', name: 'app_appointment_delete', methods: ["DELETE"])]
    public function deleteAppointment(AppointmentService $appointmentService, $id): JsonResponse
    {
        $appointments = $appointmentService->deleteAppointment($id);

        return $this->json(["appointment" => $appointments]);
    }

    #[Route('/appointment/{id}', name: 'app_appointment_update', methods: ["POST"])]
    public function update(Request $request, AppointmentService $appointmentService, $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $appointments = $appointmentService->updateAppointment($data, $id);

        return $this->json(["appointment" => $appointments]);
    }
}
