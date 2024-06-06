<?php

namespace App\Service;

use App\Entity\Appointment;
use App\Entity\Schedule;
use App\Repository\ScheduleRepository;
use App\Repository\UserRepository;
use App\Repository\AppointmentRepository;
use http\Exception\InvalidArgumentException;

class AppointmentService
{
    private ScheduleRepository $scheduleRepository;
    private UserRepository $userRepository;
    private AppointmentRepository $appointmentRepository;


    public function __construct(UserRepository $userRepository, ScheduleRepository $scheduleRepository, AppointmentRepository $appointmentRepository)
    {
        $this->userRepository = $userRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * @throws \Exception
     */
    public function createAppointment(array $data): array
    {
        $patient = $this->userRepository->findOneById($data["patient"]);
        $professional = $this->userRepository->findOneById($data["professional"]);

        $appointmentDate = new \DateTime($data['appointmentDate']);
        $startTime = new \DateTime($data['startTime']);
        $endTime = new \DateTime($data['endTime']);


        if (null === $professional || null === $patient) {
            throw new InvalidArgumentException("There is no such patient or professional");
        }

        $schedule = new Schedule();
        $schedule->setDay($appointmentDate);
        $schedule->setStartTime($startTime);
        $schedule->setEndTime($endTime);
        $schedule->setProfessional($professional);

        $this->scheduleRepository->save($schedule);

        $appointment = new Appointment();

        $appointment->setSchedule($schedule);
        $appointment->setPatient($patient);
        $appointment->setProfessional($professional);

        $this->appointmentRepository->save($appointment);

        return $appointment->serialize();

    }

    public function fetchAppointments(): array
    {
        $serialized_appointments = [];

        foreach ($this->appointmentRepository->findAll() as $appointment) {
            $serialized_appointments [] = $appointment->serialize();
        }
        return $serialized_appointments;
    }

    public function deleteAppointment(int $id): array
    {
        $appointment = $this->appointmentRepository->findById($id);

        $this->scheduleRepository->remove($appointment->getSchedule());

        $this->appointmentRepository->remove($appointment);

        return $appointment->serialize();
    }

    /**
     * @throws \Exception
     */
    public function updateAppointment(array $data, int $id): array
    {
        $appointment = $this->appointmentRepository->findById($id);

        $schedule = $appointment->getSchedule();

        if (array_key_exists("appointmentDate", $data)) {
            $appointmentDate = new \DateTime($data['appointmentDate']);
            $schedule->setDay($appointmentDate);
        }
        if (array_key_exists("startTime", $data)) {
            $startTime = new \DateTime($data['startTime']);

            $schedule->setStartTime($startTime);
        }
        if (array_key_exists("endTime", $data)) {
            $endTime = new \DateTime($data['endTime']);

            $schedule->setEndTime($endTime);
        }

        $this->scheduleRepository->save($schedule);

        $appointment->setSchedule($schedule);

        $this->appointmentRepository->save($appointment);

        return $appointment->serialize();
    }

}