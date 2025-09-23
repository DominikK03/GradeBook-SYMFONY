<?php

namespace App\Event;

use App\Entity\Homework;
use Doctrine\Common\Collections\Collection;

class NewHomeworkAdded
{
    public function __construct(private readonly Homework $homework)
    {
    }

    /**
     * @return Homework
     */
    public function getHomework(): Homework
    {
        return $this->homework;
    }

    public function getStudents(): Collection
    {
        return $this->homework->getGroup()->getStudents();
    }

}
