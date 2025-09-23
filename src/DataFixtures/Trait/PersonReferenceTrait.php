<?php

namespace App\DataFixtures\Trait;
use App\DataFixtures\PersonFixtures;
use App\Entity\Person;
use Webmozart\Assert\Assert;

trait PersonReferenceTrait
{
    /**
     * @method mixed getReference($referenceId, $className = null)
     * @method bool hasReference($referenceId, $className = null)
     */
    public function getPerson($person) : Person
    {
        $referenceName = sprintf(PersonFixtures::REFERENCE_PATTERN, $person);
        if (!$this->hasReference($referenceName, Person::class)){
            throw new \LogicException(sprintf("Person %s not found", $person));
        }
        $entity = $this->getReference($referenceName, Person::class);
        Assert::isInstanceOf($entity, Person::class);

        return $entity;
    }
}
