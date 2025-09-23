<?php

namespace App\DTO;

use App\Enum\GradeType;
use Symfony\Component\Validator\Constraints as Assert;

class GradeDto
{
    #[Assert\NotBlank(message: 'Student ID cannot be empty')]
    #[Assert\Type(type: 'numeric', message: 'Student ID must be a number')]
    #[Assert\Positive(message: 'Student ID must be a positive number')]
    public string $studentId = '';

    #[Assert\NotBlank(message: 'Grade cannot be empty')]
    #[Assert\Range(min: 1, max: 6, notInRangeMessage: 'Grade must be between {{ min }} and {{ max }}')]
    public float $grade = 0;

    #[Assert\NotBlank(message: 'Weight cannot be empty')]
    #[Assert\Range(min: 0.1, max: 10, notInRangeMessage: 'Weight must be between {{ min }} and {{ max }}')]
    public float $weight = 1;

    #[Assert\NotBlank(message: 'Grade type cannot be empty')]
    #[Assert\Choice(callback: [GradeType::class, 'cases'], message: 'Invalid grade type')]
    public string $type = '';

    #[Assert\NotBlank(message: 'Grade description cannot be empty')]
    #[Assert\Length(max: 255, maxMessage: 'Grade description cannot be longer than {{ limit }} characters')]
    public string $description = '';

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $dto = new self();
        $dto->studentId = (string) ($data['student_id'] ?? '');
        $dto->grade = (float) ($data['grade'] ?? 0);
        $dto->weight = (float) ($data['weight'] ?? 1);
        $dto->type = (string) ($data['type'] ?? '');
        $dto->description = (string) ($data['description'] ?? '');
        
        return $dto;
    }
}