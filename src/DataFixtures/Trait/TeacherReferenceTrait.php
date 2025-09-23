<?php

namespace App\DataFixtures\Trait;

use App\DataFixtures\TeacherFixtures;
use App\Entity\Teacher;
use Webmozart\Assert\Assert;

trait TeacherReferenceTrait
{
    /**
     * @method mixed getReference($referenceId, $className = null)
     * @method bool hasReference($referenceId, $className = null)
     */
    public function getTeacher(string $teacher) : Teacher
    {
        $referenceName = sprintf(TeacherFixtures::REFERENCE_PATTERN, $teacher);
        if (!$this->hasReference($referenceName, Teacher::class)){
            throw new \LogicException(sprintf("Teacher %s not found", $teacher));
        }
        $entity = $this->getReference($referenceName, Teacher::class);
        Assert::isInstanceOf($entity, Teacher::class);

        return $entity;
    }

}
