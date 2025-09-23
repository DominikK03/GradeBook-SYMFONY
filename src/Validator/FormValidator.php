<?php

namespace App\Validator;

use App\DTO\GradeDto;
use App\Service\CsrfValidationService;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class FormValidator
{
    public function __construct(
        private ValidatorInterface $validator,
        private CsrfValidationService $csrfValidationService
    ) {
    }

    /**
     * @param array<string, mixed> $formData
     * @return array<string>
     */
    public function validateGradeForm(array $formData): array
    {
        $errors = [];

        if (!$this->csrfValidationService->validateGradeFormToken($formData)) {
            $errors[] = 'Invalid security token.';
        }

        $dto = GradeDto::fromArray($formData);
        $violations = $this->validator->validate($dto);

        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }

        return $errors;
    }

    /**
     * @param object $dto
     * @return array<string>
     */
    public function validateDto(object $dto): array
    {
        $errors = [];
        $violations = $this->validator->validate($dto);

        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }

        return $errors;
    }
}