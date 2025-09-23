<?php

namespace App\Entity;

use AllowDynamicProperties;
use App\Enum\ClassName;
use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[AllowDynamicProperties] #[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    public const string SCHOOLYEAR = '2025/2026';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 9, nullable: false)]
    private ?string $schoolYear = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'groupTeacherID', referencedColumnName: 'id', nullable: false)]
    private ?Teacher $groupTeacher = null;

    /**
     * @var Collection<int, Student>
     */
    #[ORM\OneToMany(targetEntity: Student::class, mappedBy: 'class')]
    private Collection $students;


    #[ORM\Column(enumType: ClassName::class)]
    private ?ClassName $name = null;

    /**
     * @var Collection<int, Homework>
     */
    #[ORM\OneToMany(targetEntity: Homework::class, mappedBy: 'group')]
    private Collection $homework;

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->homework = new ArrayCollection();
    }
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchoolYear(): ?string
    {
        return $this->schoolYear;
    }

    public function setSchoolYear(string $schoolYear): static
    {
        $this->schoolYear = $schoolYear;

        return $this;
    }

    public function getGroupTeacher(): ?Teacher
    {
        return $this->groupTeacher;
    }

    public function setGroupTeacher(Teacher $groupTeacher): static
    {
        $this->groupTeacher = $groupTeacher;

        return $this;
    }

    public function getGroupID(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setGroup($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        if ($this->students->removeElement($student)) {
            if ($student->getGroup() === $this) {
                $student->setGroup(null);
            }
        }

        return $this;
    }


    public function getName(): ?ClassName
    {
        return $this->name;
    }

    public function setName(ClassName $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName()->value;
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
            $homework->setGroup($this);
        }

        return $this;
    }

    public function removeHomework(Homework $homework): static
    {
        if ($this->homework->removeElement($homework)) {
            if ($homework->getGroup() === $this) {
                $homework->setGroup(null);
            }
        }

        return $this;
    }
}
