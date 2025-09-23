<?php

namespace App\DataFixtures;

use App\DataFixtures\Trait\GroupReferenceTrait;
use App\DataFixtures\Trait\TeacherReferenceTrait;
use App\Entity\Schedule;
use App\Enum\Classroom;
use App\Enum\Subject;
use App\Enum\Weekday;
use App\Service\GroupService;
use App\Service\TeacherService;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\DataFixtures\Trait\ScheduleReferenceTrait;

class ScheduleFixtures extends Fixture implements DependentFixtureInterface
{
    use ScheduleReferenceTrait;
    use TeacherReferenceTrait;
    use GroupReferenceTrait;

    public const REFERENCE_PATTERN = 'schedule-%s-%s-%s';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('pl_PL');
        $timeSlots = [
            ['08:00', '08:45'],
            ['09:00', '09:45'],
            ['10:00', '10:45'],
            ['11:00', '11:45'],
            ['12:00', '12:45'],
            ['13:00', '13:45'],
            ['14:00', '14:45'],
            ['15:00', '15:45']
        ];
        
        for ($i = 0; $i < 15; $i++) {
            $schedule = new Schedule();

            $timeSlot = $faker->randomElement($timeSlots);
            $startTime = DateTime::createFromFormat('H:i', $timeSlot[0]);
            $endTime = DateTime::createFromFormat('H:i', $timeSlot[1]);
            
            $schedule->setStartTime($startTime);
            $schedule->setEndTime($endTime);
            $schedule->setClassroom($faker->randomElement(Classroom::cases()));
            $schedule->setWeekday($faker->randomElement(Weekday::cases()));
            
            $teacher = $this->getTeacher(TeacherService::getRandomTeacherSpecializationForFixture());
            $schedule->setSubject($teacher->getSpecialization()[0]);
            $schedule->setTeacher($teacher);
            $schedule->setClass($this->getGroup(GroupService::getRandomGroupForFixture()));
            
            $manager->persist($schedule);
            $this->addReference(sprintf(self::REFERENCE_PATTERN, $schedule->getWeekday()->value, $schedule->getStartTime()->format('H:i'), $i), $schedule);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TeacherFixtures::class,
            GroupFixtures::class
        ];
    }
}
