<?php

namespace App\DataFixtures\Trait;

use App\DataFixtures\GroupFixtures;
use App\Entity\Group;
use Webmozart\Assert\Assert;

trait GroupReferenceTrait
{
    /**
     * @method mixed getReference($referenceId, $className = null)
     * @method bool hasReference($referenceId, $className = null)
     */
    public function getGroup(string $group) : Group
    {
        $referenceName = sprintf(GroupFixtures::REFERENCE_PATTERN, $group);
        if (!$this->hasReference($referenceName, Group::class)){
            throw new \LogicException(sprintf("Group %s not found", $group));
        }
        $entity = $this->getReference($referenceName, Group::class);
        Assert::isInstanceOf($entity, Group::class);

        return $entity;
    }

}
