<?php

namespace App\Service;

use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final readonly class CsrfValidationService
{
    public function __construct(
        private CsrfTokenManagerInterface $csrfTokenManager
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function isTokenValid(array $data, string $tokenId): bool
    {
        if (!isset($data['_token'])) {
            return false;
        }

        $token = new CsrfToken($tokenId, $data['_token']);
        return $this->csrfTokenManager->isTokenValid($token);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function validateGradeFormToken(array $data): bool
    {
        return $this->isTokenValid($data, 'grade_form');
    }
}