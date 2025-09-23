<?php

namespace App\Enum;
enum GradeType : string
{
    case HOMEWORK = 'HOMEWORK';
    case EXAM = 'EXAM';
    case PROJECT = 'PROJECT';
    case QUIZ = 'QUIZ';
}
