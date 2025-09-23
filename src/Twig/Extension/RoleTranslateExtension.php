<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\AppExtensionRuntime;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class RoleTranslateExtension extends AbstractExtension
{
    public function __construct(protected TranslatorInterface $translator)
    {
    }
    public function getFilters(): array
    {
        return [
            new TwigFilter('translate_role', [$this, 'translateRole'])
        ];
    }
    public function translateRole(string $role): string
    {
        if (!$role) {
            return '';
        }
        return $this->translator->trans($role, domain: 'user_roles');
    }
}
