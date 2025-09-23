<?php

namespace App\Entity;

use App\Enum\Subject;
use App\Repository\TeacherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeacherRepository::class)]
class Teacher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:Types::SIMPLE_ARRAY, nullable:false, enumType:Subject::class)]
    private array $specialization = [];

    /**
     * @var Collection<int, Schedule>
     */
    #[ORM\OneToMany(targetEntity: Schedule::class, mappedBy: 'teacher')]
    private Collection $schedules;

    #[ORM\OneToOne(cascade: ['persist', 'remove'], inversedBy: 'teacher')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\OneToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Person $person = null;

    /**
     * @var Collection<int, Homework>
     */
    #[ORM\OneToMany(targetEntity: Homework::class, mappedBy: 'teacher')]
    private Collection $homework;

    public function __construct()
    {
        $this->schedules = new ArrayCollection();
        $this->homework = new ArrayCollection();
    }

    /**
     * @return Subject[]
     */
    public function getSpecialization(): array
    {
        return $this->specialization;
    }

    public function setSpecialization(array $specialization): static
    {
        $this->specialization = $specialization;

        return $this;
    }
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
            $schedule->setTeacher($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): static
    {
        if ($this->schedules->removeElement($schedule)) {
            if ($schedule->getTeacher() === $this) {
                $schedule->setTeacher(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): static
    {
        $this->person = $person;

        return $this;
    }

    /**
     * @return Collection<int, Homework>
     */
    public function getHomework(): Collection
    {
        return $this->homework;
    }

    public function addHomework(Homework $homework): static
    {
        if (!$this->homework->contains($homework)) {
            $this->homework->add($homework);
            $homework->setTeacher($this);
        }

        return $this;
    }

    public function removeHomework(Homework $homework): static
    {
        if ($this->homework->removeElement($homework)) {
            if ($homework->getTeacher() === $this) {
                $homework->setTeacher(null);
            }
        }

        return $this;
    }
}
