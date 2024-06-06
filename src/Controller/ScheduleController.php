<?php

namespace App\Controller;

use App\Service\ScheduleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ScheduleController extends AbstractController
{
    #[Route('/schedule', name: 'app_schedule_create', methods: ["POST"])]
    public function createSchedule(Request $request, ScheduleService $scheduleService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $schedule = $scheduleService->createSchedule($data);

        return $this->json(["schedule" => $schedule]);
    }

    #[Route('/schedule', name: 'app_schedule_read_all', methods: ["GET"])]
    public function fetchSchedules(ScheduleService $scheduleService): JsonResponse
    {
        $schedules = $scheduleService->fetchSchedules();
        return $this->json(["schedules" => $schedules]);
    }

    #[Route('/schedule/{id}', name: 'app_schedule_read_one', methods: ["GET"])]
    public function fetchScheduleById(ScheduleService $scheduleService, $id): JsonResponse
    {
        $schedule = $scheduleService->fetchScheduleById($id);

        return $this->json(["schedule" => $schedule]);
    }

    #[Route('/schedule/{id}', name: 'app_schedule_update', methods: ["POST"])]
    public function updateSchedule(ScheduleService $scheduleService, $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $schedule = $scheduleService->updateSchedule($data, $id);


        return $this->json(["schedule" => $schedule]);
    }

    #[Route('/freeHours/{id}', name: 'app_schedule_free_hours', methods: ["GET"])]
    public function getFreeHours(ScheduleService $scheduleService, $id): JsonResponse
    {
        return $this->json(["schedule" => $scheduleService->getProfessionalFreeHours($id)]);
    }
}
