<?php

namespace App\Service;

use App\Entity\Schedule;
use App\Repository\ScheduleRepository;
use App\Repository\UserRepository;
use http\Exception\InvalidArgumentException;

class ScheduleService
{
    private ScheduleRepository $scheduleRepository;

    private UserRepository $userRepository;


    public function __construct(ScheduleRepository $scheduleRepository, UserRepository $userRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
        $this->userRepository = $userRepository;
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


    public function getProfessionalFreeHours(int $professionalId): array
    {
        $startDate = new \DateTime();
        $endDate = clone $startDate;
        $endDate->modify('+3 weekdays'); // Get the date for the next 4 working days

        $startDateOneMinus = clone $startDate;
        $startDateOneMinus->modify('-1 weekday');
        $endDatePlusOne = clone $endDate;
        $endDatePlusOne->modify('+1 weekday');

        $professional = $this->userRepository->findOneById($professionalId);

        $schedules = $this->scheduleRepository->getSchedules($professional, $startDateOneMinus, $endDatePlusOne);


        $freeHours = [];

        $day = [];


        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            if ($currentDate->format('N') <= 5) {
                $workingHours = $this->getWorkingHours($currentDate);
                $daySchedules = array_filter($schedules, function ($schedule) use ($currentDate) {
                    return $schedule->getDay()->format('Y-m-d') === $currentDate->format('Y-m-d');
                });
                $freeHoursForDay = $this->getFreeHoursForDay($workingHours, $daySchedules);

                $day[] = $freeHoursForDay;

                $freeHours[$currentDate->format('Y-m-d')] = $freeHoursForDay;
            }

            $currentDate->modify('+1 day');
        }

        return $freeHours;
    }

    private function getWorkingHours(\DateTime $date): array
    {
        return [
            'startTime' => \DateTime::createFromFormat('H:i', '09:00'),
            'endTime' => \DateTime::createFromFormat('H:i', '17:00'),
        ];
    }

    private function getFreeHoursForDay(array $workingHours, $daySchedules): array
    {
        $freeHours = [];


        $lunchStartTime = \DateTime::createFromFormat('H:i', '12:00');
        $lunchEndTime = \DateTime::createFromFormat('H:i', '14:00');


        $bookedSlots = [];

        $schedules = is_array($daySchedules) ? $daySchedules : [$daySchedules];

        // Create an array of booked slots
        foreach ($schedules as $schedule) {
            $scheduleStartTime = $schedule->getStartTime();
            $scheduleEndTime = $schedule->getEndTime();

            $startTime = clone $scheduleStartTime;
            while ($startTime < $scheduleEndTime) {
                $endTime = clone $startTime;
                $endTime->modify('+1 hour');

                $bookedSlots[] = [
                    'startTime' => $startTime->format('H:i'),
                    'endTime' => $endTime->format('H:i'),
                ];

                $startTime = $endTime;
            }
        }

        $booked = [];

        $currentTime = clone $workingHours['startTime'];
        while ($currentTime < $workingHours['endTime']) {
            $nextHour = clone $currentTime;
            $nextHour->modify('+1 hour');

            if ($currentTime >= $lunchStartTime && $currentTime < $lunchEndTime) {
                $currentTime = $lunchEndTime;
                continue;
            }


            $isBooked = false;
            foreach ($bookedSlots as $bookedSlot) {
                $booked [] = [$bookedSlot["startTime"], $bookedSlot["endTime"]];
                if ($currentTime->format("H:i") >= $bookedSlot['startTime'] && $nextHour->format("H:i") <= $bookedSlot['endTime']) {
                    $isBooked = true;
                    break;
                }
            }

            if (!$isBooked) {
                $freeHours[] = [
                    'startTime' => $currentTime->format('H:i'),
                    'endTime' => $nextHour->format('H:i'),
                ];
            }

            $currentTime = $nextHour;
        }


        return $freeHours;
    }
}