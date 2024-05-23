<?php

namespace App\Service;

use App\Entity\Schedule;
use App\Repository\ScheduleRepository;
use App\Repository\UserRepository;
use http\Exception\InvalidArgumentException;
use mysql_xdevapi\Schema;

class ScheduleService
{
    private ScheduleRepository $scheduleRepository;


    public function __construct(ScheduleRepository $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function createSchedule(array $data): array
    {
        $schedule = new Schedule();
        $this->validateRequestData($data);
        $schedule->setProfessional($data["professional"]);
        $schedule->setDay($data["day"]);
        $schedule->setStartTime($data["startTime"]);
        $schedule->setEndTime($data["endTime"]);

        $this->scheduleRepository->save($schedule);

        return $schedule->serialize();
    }

    public function fetchSchedules(): array
    {
        $serialized_schedules = [];

        foreach ($this->scheduleRepository->findAll() as $schedule) {
            $serialized_schedules [] = $schedule->serialize();
        }
        return $serialized_schedules;
    }

    public function fetchScheduleById(int $id): array
    {
        $schedule = $this->scheduleRepository->findOneById($id);
        return $schedule->serialize();
    }

    public function deleteSchedule(int $id): array
    {
        $schedule = $this->scheduleRepository->findOneById($id);

        $this->scheduleRepository->remove($schedule);

        return $schedule->serialize();
    }

    public function updateSchedule(array $data, int $id): array
    {
        $schedule = $this->scheduleRepository->findOneById($id);
        if (array_key_exists("day", $data)) {
            $schedule->setDay($data["day"]);
        }
        if (array_key_exists("startTime", $data)) {
            $schedule->setStartTime($data["startTime"]);
        }
        if (array_key_exists("endTime", $data)) {
            $schedule->setEndTime($data["endTime"]);
        }

        $this->scheduleRepository->save($schedule);

        return $schedule->serialize();
    }

    private function validateRequestData(array $data): void
    {
        $requestFields = ["professional", "day", "startTime", "endTime"];
        foreach ($requestFields as $field) {
            if (!array_key_exists($field, $data)) {
                throw new InvalidArgumentException("Invalid arguments missing '$field'");
            }
        }
    }
}