<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use function Symfony\Component\Translation\t;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?string $phoneNumber = null;

    #[ORM\Column]
    private ?bool $patient = null;

    #[ORM\Column]
    private ?bool $professional = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $speciality = null;


    /**
     * @var Collection<int, Schedule>
     */
    #[ORM\OneToMany(targetEntity: Schedule::class, mappedBy: 'professional')]
    private Collection $schedules;

    public function __construct()
    {
        $this->schedules = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function isPatient(): ?bool
    {
        return $this->patient;
    }

    public function setPatient(bool $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function isProfessional(): ?bool
    {
        return $this->professional;
    }

    public function setProfessional(bool $professional): static
    {
        $this->professional = $professional;

        return $this;
    }

    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    public function setSpeciality(?string $speciality): static
    {
        $this->speciality = $speciality;

        return $this;
    }


    public  function serialize() : array
    {
        $serialized_schedules = [];
        foreach ($this->getSchedules()->toArray() as $schedule){
            $serialized_schedules [] = $schedule->serialize();
            }

        return [
            "id"=>$this->getId(),
            "name"=>$this->getName(),
            "lastname"=>$this->getLastname(),
            "email"=>$this->getEmail(),
            "password"=>$this->getPassword(),
            "location"=>$this->getLocation(),
            "phoneNumber"=>$this->getPhoneNumber(),
            "patient"=>$this->isPatient(),
            "professional"=>$this->isProfessional(),
            "speciality"=>$this->getSpeciality(),
            "schedules"=>$serialized_schedules
        ];
    }

    public function getRoles(): array
    {
        return [];
        // TODO: Implement getRoles() method.
    }

    public function eraseCredentials() : void
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }


    /**
     * @return Collection<int, Schedule>
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(Schedule $schedule): static
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules->add($schedule);
            $schedule->setProfessional($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): static
    {
        if ($this->schedules->removeElement($schedule)) {
            // set the owning side to null (unless already changed)
            if ($schedule->getProfessional() === $this) {
                $schedule->setProfessional(null);
            }
        }

        return $this;
    }

}
