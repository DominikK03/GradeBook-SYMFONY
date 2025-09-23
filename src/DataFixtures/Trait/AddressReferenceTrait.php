<?php

namespace App\DataFixtures\Trait;
use App\DataFixtures\AddressFixtures;
use App\Entity\Address;
use Webmozart\Assert\Assert;

trait AddressReferenceTrait
{
    /**
     * @method mixed getReference($referenceId, $className = null)
     * @method bool hasReference($referenceId, $className = null)
     */
    public function getAddress($address) : Address
    {
        $referenceName = sprintf(AddressFixtures::REFERENCE_PATTERN, $address);
        if (!$this->hasReference($referenceName, Address::class)){
            throw new \LogicException(sprintf("Address %s not found", $address));
        }
        $entity = $this->getReference($referenceName, Address::class);
        Assert::isInstanceOf($entity, Address::class);

        return $entity;
    }
}
