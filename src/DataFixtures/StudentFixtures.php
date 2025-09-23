<?php

namespace App\DataFixtures;

use App\DataFixtures\Trait\GroupReferenceTrait;
use App\DataFixtures\Trait\PersonReferenceTrait;
use App\DataFixtures\Trait\UserReferenceTrait;
use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class StudentFixtures extends Fixture implements DependentFixtureInterface
{
    use UserReferenceTrait;
    use GroupReferenceTrait;
    use PersonReferenceTrait;


    public const REFERENCE_PATTERN = 'student-%d';
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('pl-PL');
        
        $student = new Student();
        $student->setUser($this->getUser('student'));
        $student->setPerson($this->getPerson(1));
        $student->setGroup($this->getGroup($faker->randomElement(["a1", "b1", "c2"])));
        $manager->persist($student);

        for ($i = 2; $i <= 15; $i++) {
            $student = new Student();
            $student->setUser(null);
            $student->setPerson($this->getPerson($i + 4));
            $student->setGroup($this->getGroup($faker->randomElement(["a1", "b1", "c2"])));
            $manager->persist($student);
            $this->addReference(sprintf(self::REFERENCE_PATTERN, $i), $student);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            GroupFixtures::class,
            PersonFixtures::class
        ];
    }
}
