<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class HomeworkDto
{
    #[Assert\NotBlank(message: 'Topic cannot be empty')]
    #[Assert\Length(max: 255, maxMessage: 'Topic cannot be longer than {{ limit }} characters')]
    public string $topic = '';

    #[Assert\Length(max: 1000, maxMessage: 'Description cannot be longer than {{ limit }} characters')]
    public ?string $description = null;

    #[Assert\NotBlank(message: 'Due date cannot be empty')]
    #[Assert\Date(message: 'Due date must be a valid date')]
    public string $dueDate = '';

    #[Assert\NotBlank(message: 'Class ID cannot be empty')]
    #[Assert\Type(type: 'numeric', message: 'Class ID must be a number')]
    #[Assert\Positive(message: 'Class ID must be a positive number')]
    public string $classId = '';

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->topic = (string) ($data['topic'] ?? '');
        $dto->description = isset($data['description']) ? (string) $data['description'] : null;
        $dto->dueDate = (string) ($data['dueDate'] ?? '');
        $dto->classId = (string) ($data['class'] ?? '');
        
        return $dto;
    }
}