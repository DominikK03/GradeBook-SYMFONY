<?php

namespace App\Service;

use App\Entity\Student;
use App\Entity\Teacher;
use App\Repository\ScheduleRepository;

final readonly class ScheduleService
{
    public function __construct(private ScheduleRepository $scheduleRepository)
    {
    }

    public function getSchedule(Teacher|Student $user): array
    {
        if ($user instanceof Teacher) {
            $scheduleData = $this->scheduleRepository->findByTeacher($user);
        }
        else {
            $scheduleData = $this->scheduleRepository->findByStudent($user);
        }
        
        return $this->prepareSchedule($scheduleData);
    }
    
    private function prepareSchedule(array $scheduleData): array
    {
        $organizedSchedule = [];
        
        $weekdayMap = [
            'monday' => 1,
            'tuesday' => 2, 
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5
        ];
        
        foreach ($scheduleData as $lesson) {
            $weekdayString = $lesson->getWeekday()->value;
            $dayNumber = $weekdayMap[$weekdayString] ?? null;
            
            if ($dayNumber === null) continue;
            
            $startTime = $lesson->getStartTime()->format('H:i');
            
            if (!isset($organizedSchedule[$dayNumber])) {
                $organizedSchedule[$dayNumber] = [];
            }
            
            if (!isset($organizedSchedule[$dayNumber][$startTime])) {
                $organizedSchedule[$dayNumber][$startTime] = [];
            }
            
            $organizedSchedule[$dayNumber][$startTime][] = $lesson;
        }

        ksort($organizedSchedule);
        foreach ($organizedSchedule as &$daySchedule) {
            ksort($daySchedule);
        }
        
        return $organizedSchedule;
    }
}
