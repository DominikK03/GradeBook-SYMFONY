<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\OneToOne(cascade: ['persist', 'remove'], inversedBy: 'student')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\OneToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Person $person = null;
    #[ORM\ManyToOne(inversedBy: 'students')]

    #[ORM\JoinColumn(name: 'groupID', referencedColumnName: 'id', nullable: false)]
    private ?Group $class = null;

    /**
     * @var Collection<int, Grade>
     */
    #[ORM\OneToMany(targetEntity: Grade::class, mappedBy: 'student', orphanRemoval: true)]
    private Collection $grades;

    #[ORM\ManyToOne(inversedBy: 'student')]
    private ?StudentParent $studentParent = null;

    public function __construct()
    {
        $this->grades = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    public function getGroup(): ?Group
    {
        return $this->class;
    }

    public function setGroup(?Group $class): static
    {
        $this->class = $class;

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
     * @return Collection<int, Grade>
     */
    public function getGrades(): Collection
    {
        return $this->grades;
    }

    public function addGrade(Grade $grade): static
    {
        if (!$this->grades->contains($grade)) {
            $this->grades->add($grade);
            $grade->setStudent($this);
        }

        return $this;
    }

    public function removeGrade(Grade $grade): static
    {
        if ($this->grades->removeElement($grade)) {
            if ($grade->getStudent() === $this) {
                $grade->setStudent(null);
            }
        }

        return $this;
    }

    public function getStudentParent(): ?StudentParent
    {
        return $this->studentParent;
    }

    public function setStudentParent(?StudentParent $studentParent): static
    {
        $this->studentParent = $studentParent;

        return $this;
    }
}
