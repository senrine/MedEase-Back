<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $patient;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $professional;

    /**
     * @var Collection<int, Schedule>
     */
    #[ORM\ManyToMany(targetEntity: Schedule::class)]
    private Collection $schedule;

    public function __construct()
    {
        $this->patient = new ArrayCollection();
        $this->professional = new ArrayCollection();
        $this->schedule = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getPatient(): Collection
    {
        return $this->patient;
    }

    public function addPatient(User $patient): static
    {
        if (!$this->patient->contains($patient)) {
            $this->patient->add($patient);
        }

        return $this;
    }

    public function removePatient(User $patient): static
    {
        $this->patient->removeElement($patient);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getProfessional(): Collection
    {
        return $this->professional;
    }

    public function addProfessional(User $professional): static
    {
        if (!$this->professional->contains($professional)) {
            $this->professional->add($professional);
        }

        return $this;
    }

    public function removeProfessional(User $professional): static
    {
        $this->professional->removeElement($professional);

        return $this;
    }

    /**
     * @return Collection<int, Schedule>
     */
    public function getSchedule(): Collection
    {
        return $this->schedule;
    }

    public function addSchedule(Schedule $schedule): static
    {
        if (!$this->schedule->contains($schedule)) {
            $this->schedule->add($schedule);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): static
    {
        $this->schedule->removeElement($schedule);

        return $this;
    }
}
