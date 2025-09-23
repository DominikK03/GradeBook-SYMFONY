<?php

namespace App\DataFixtures;

use App\DataFixtures\Trait\PersonReferenceTrait;
use App\DataFixtures\Trait\UserReferenceTrait;
use App\Entity\Person;
use App\Entity\Teacher;
use App\Enum\Subject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TeacherFixtures extends Fixture implements DependentFixtureInterface
{
    use UserReferenceTrait;
    use PersonReferenceTrait;

    public const array TEACHERS = [
        'teacher-1' => [
            'name' => 'teacher',
            'specialization' => Subject::BIOLOGY,
        ],
        'teacher-2' => [
            'name' => 2,
            'specialization' => Subject::MATHEMATICS,
        ],
        'teacher-3' => [
            'name' => 3,
            'specialization' => Subject::CHEMISTRY,
        ],
        'teacher-4' => [
            'name' => 4,
            'specialization' => Subject::ENGLISH,
        ],
    ];

    public const string REFERENCE_PATTERN = 'teacher-%s';

    public function load(ObjectManager $manager): void
    {
        $personIndex = 1;
        foreach (self::TEACHERS as $teacherKey => $teacherData) {
            $teacher = new Teacher();
            if($teacherData['name'] == 'teacher'){
                $teacher->setUser($this->getUser($teacherData['name']));
                $teacher->setPerson($this->getPerson(2)); // person 2 for logged in teacher
            } else {
                $teacher->setPerson($this->getPerson($personIndex + 2)); // persons 3, 4, 5 for other teachers
                $personIndex++;
            }
            $teacher->setSpecialization([$teacherData['specialization']]);
            $manager->persist($teacher);
            $this->addReference(sprintf(self::REFERENCE_PATTERN, strtolower($teacherData['specialization']->value)), $teacher);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PersonFixtures::class,
        ];
    }
}
