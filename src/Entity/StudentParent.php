<?php

namespace App\Entity;

use App\Repository\StudentParentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentParentRepository::class)]
class StudentParent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'], inversedBy: 'studentParent')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\OneToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Person $person = null;

    /**
     * @var Collection<int, Student>
     */
    #[ORM\OneToMany(targetEntity: Student::class, mappedBy: 'studentParent')]
    private Collection $student;

    #[ORM\Column(nullable: true)]
    private ?string $contactEmail;
    #[ORM\Column(nullable: true)]
    private ?string $contactPhone;

    public function __construct()
    {
        $this->student = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection<int, Student>
     */
    public function getStudent(): Collection
    {
        return $this->student;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->student->contains($student)) {
            $this->student->add($student);
            $student->setStudentParent($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        if ($this->student->removeElement($student)) {
            if ($student->getStudentParent() === $this) {
                $student->setStudentParent(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    /**
     * @return string|null
     */
    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    /**
     * @param string|null $contactEmail
     */
    public function setContactEmail(?string $contactEmail): void
    {
        $this->contactEmail = $contactEmail;
    }

    /**
     * @param string|null $contactPhone
     */
    public function setContactPhone(?string $contactPhone): void
    {
        $this->contactPhone = $contactPhone;
    }
}
