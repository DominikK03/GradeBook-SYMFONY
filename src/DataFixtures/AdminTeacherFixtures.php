<?php

namespace App\DataFixtures;

use App\DataFixtures\Trait\PersonReferenceTrait;
use App\DataFixtures\Trait\UserReferenceTrait;
use App\Entity\Teacher;
use App\Enum\Subject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AdminTeacherFixtures extends Fixture implements DependentFixtureInterface
{
    use PersonReferenceTrait;
    use UserReferenceTrait;

    public function load(ObjectManager $manager): void
    {
        // Utwórz Teacher dla admina używając Person o ID 1 i User admin
        $adminTeacher = new Teacher();
        $adminTeacher->setPerson($this->getPerson(1)); // Adam Administrator
        $adminTeacher->setUser($this->getUser('admin')); // admin@poczta.pl
        $adminTeacher->setSpecialization([
            Subject::MATHEMATICS,
            Subject::PHYSICS,
            Subject::CHEMISTRY
        ]);

        $manager->persist($adminTeacher);
        $this->addReference('teacher_admin', $adminTeacher);
        
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PersonFixtures::class,
            UserFixtures::class
        ];
    }
}