<?php

namespace App\Entity;

use App\Enum\Role;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_STUDENT = 'ROLE_STUDENT';
    public const ROLE_TEACHER = 'ROLE_TEACHER';
    public const ROLE_PARENT = 'ROLE_PARENT';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column()]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToOne(targetEntity: Student::class, mappedBy: 'user', fetch: 'EXTRA_LAZY')]
    private ?Student $student = null;

    #[ORM\OneToOne(targetEntity: Teacher::class, mappedBy: 'user', fetch: 'EXTRA_LAZY')]
    private ?Teacher $teacher = null;

    #[ORM\OneToOne(targetEntity: StudentParent::class, mappedBy: 'user', fetch: 'EXTRA_LAZY')]
    private ?StudentParent $studentParent = null;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = self::ROLE_USER;

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
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

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): static
    {
        $this->teacher = $teacher;
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

    public function getPerson(): ?Person
    {
        $roles = $this->getRoles();
        
        if (in_array(self::ROLE_STUDENT, $roles)) {
            return $this->getStudent()?->getPerson();
        }
        
        if (in_array(self::ROLE_TEACHER, $roles)) {
            return $this->getTeacher()?->getPerson();
        }
        
        if (in_array(self::ROLE_PARENT, $roles)) {
            return $this->getStudentParent()?->getPerson();
        }
        
        return $this->getStudent()?->getPerson()
            ?? $this->getTeacher()?->getPerson() 
            ?? $this->getStudentParent()?->getPerson();
    }
    
    public function getRoleEntity(): Student|Teacher|StudentParent|null
    {
        $roles = $this->getRoles();
        
        if (in_array(self::ROLE_STUDENT, $roles)) {
            return $this->getStudent();
        }
        
        if (in_array(self::ROLE_TEACHER, $roles)) {
            return $this->getTeacher();
        }
        
        if (in_array(self::ROLE_PARENT, $roles)) {
            return $this->getStudentParent();
        }
        
        return null;
    }
}
