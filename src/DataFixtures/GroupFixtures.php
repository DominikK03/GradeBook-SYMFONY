<?php

namespace App\DataFixtures;

use App\DataFixtures\Trait\TeacherReferenceTrait;
use App\Entity\Group;
use App\Enum\ClassName;
use App\Service\TeacherService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GroupFixtures extends Fixture implements DependentFixtureInterface
{
    use TeacherReferenceTrait;
    public const array GROUPS = [
            'a1' => [
                    'school_year' => Group::SCHOOLYEAR,
                    'name' => ClassName::A1,
            ],
            'b1' => [
                    'school_year' => Group::SCHOOLYEAR,
                    'name' => ClassName::B1,

            ],
            'c2' => [
                    'school_year' => Group::SCHOOLYEAR,
                    'name' => ClassName::C2,

            ],
    ];

    public const string REFERENCE_PATTERN = 'group-%s';
    public function load(ObjectManager $manager): void
    {
        foreach (self::GROUPS as $groupName => $groupData) {
                $group = new Group();
                $group->setSchoolYear($groupData['school_year']);
                $group->setName($groupData['name']);
                $group->setGroupTeacher($this->getTeacher(TeacherService::getRandomTeacherSpecializationForFixture()));
                $manager->persist($group);
                $this->addReference(sprintf(self::REFERENCE_PATTERN, $groupName), $group);

        }
            $manager->flush();
        }

        public function getDependencies(): array
        {
            return [
                    TeacherFixtures::class
            ];
        }
    }
