<?php

namespace App\DataFixtures\Trait;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use Webmozart\Assert\Assert;

trait UserReferenceTrait
{
    /**
     * @method mixed getReference($referenceId, $className = null)
     * @method bool hasReference($referenceId, $className = null)
     */
    public function getUser(string $user) : User
    {
        $referenceName = sprintf(UserFixtures::REFERENCE_PATTERN, $user);
        if (!$this->hasReference($referenceName, User::class)){
            throw new \LogicException(sprintf("User %s not found", $user));
        }
        $entity = $this->getReference($referenceName, User::class);
        Assert::isInstanceOf($entity, User::class);

        return $entity;
    }

}
