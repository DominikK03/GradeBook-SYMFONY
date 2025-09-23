<?php

namespace App\DataFixtures;

use App\DataFixtures\Trait\PersonReferenceTrait;
use App\Entity\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AddressFixtures extends Fixture
{
    use PersonReferenceTrait;
    public const REFERENCE_PATTERN = 'address_%s';
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('pl-PL');
        for($i = 1; $i <= 20; $i++) {

            $address = new Address();
            $address->setCountry('Polska');
            $address->setCity($faker->city());
            $address->setStreet($faker->streetName());
            $address->setPostalCode(sprintf("%s-%s", $faker->randomNumber(2), $faker->randomNumber(3)));
            $address->setVoivodeship($faker->streetAddress());
            $address->setAddressNumber($faker->buildingNumber());

            $manager->persist($address);
            $this->addReference(sprintf(self::REFERENCE_PATTERN, $i), $address);
        }
        $manager->flush();
    }
}
