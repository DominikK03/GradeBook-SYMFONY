<?php

namespace App\Entity;

use App\Enum\GradeType;
use App\Enum\Subject;
use App\Repository\GradeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GradeRepository::class)]
class Grade
{
    public const int MIN_VALUE = 1;
    public const int MAX_VALUE = 6;
    public const int MIN_WAGE = 1;
    public const int MAX_WAGE = 3;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $value = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(enumType: GradeType::class)]
    private ?GradeType $type = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(referencedColumnName: 'id', nullable: false)]
    private ?Teacher $teacher = null;

    #[ORM\Column(enumType: Subject::class)]
    private ?Subject $subject = null;

    #[ORM\Column(nullable: false)]
    private ?int $wage = null;

    #[ORM\Column(type:'text', nullable: true)]
    private ?string $description = null;
    #[ORM\ManyToOne(inversedBy: 'grades')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $student = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?GradeType
    {
        return $this->type;
    }

    public function setType(GradeType $type): static
    {
        $this->type = $type;

        return $this;
    }
    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): static
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function setSubject(Subject $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getWage(): ?int
    {
        return $this->wage;
    }

    public function setWage(int $wage): static
    {
        if ($wage < self::MIN_WAGE || $wage > self::MAX_WAGE) {
            throw new \InvalidArgumentException('Wage must be between ' . self::MIN_WAGE . ' and ' . self::MAX_WAGE);
        }
        
        $this->wage = $wage;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): static
    {
        $this->student = $student;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
