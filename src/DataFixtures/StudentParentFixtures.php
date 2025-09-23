<?php

namespace App\DataFixtures;

use App\Entity\StudentParent;
use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class StudentParentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $person = $manager->getRepository(Person::class)->findOneBy([]);
        
        if ($person) {
            $parent = new StudentParent();
            $parent->setPerson($person);
            $parent->setContactEmail('dominikkepczyk@o2.pl');
            $parent->setContactPhone('+48123456789');
            
            $manager->persist($parent);
            $manager->flush();
            
            $this->addReference('parent_dominik', $parent);
        }
    }

    public function getDependencies(): array
    {
        return [
            PersonFixtures::class,
        ];
    }
}
