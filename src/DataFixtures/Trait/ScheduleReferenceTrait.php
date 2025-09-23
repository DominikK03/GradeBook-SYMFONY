<?php

namespace App\DataFixtures\Trait;

use App\DataFixtures\ScheduleFixtures;
use App\Entity\Schedule;
use Webmozart\Assert\Assert;

trait ScheduleReferenceTrait
{
    /**
     * @method mixed getReference($referenceId, $className = null)
     * @method bool hasReference($referenceId, $className = null)
     */
    public function getSchedule(string $weekday, string $hour, int $index): Schedule
    {
        $referenceName = sprintf(ScheduleFixtures::REFERENCE_PATTERN, $weekday, $hour, $index);
        if (!$this->hasReference($referenceName, Schedule::class)) {
            throw new \LogicException(sprintf("Schedule %s-%s-%d not found", $weekday, $hour, $index));
        }
        $entity = $this->getReference($referenceName, Schedule::class);
        Assert::isInstanceOf($entity, Schedule::class);
        return $entity;
    }
}

