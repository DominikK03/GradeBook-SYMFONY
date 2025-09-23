<?php

namespace App\Enum;
enum Role : string
{
    case ADMIN = 'ROLE_ADMIN';
    case TEACHER = 'ROLE_TEACHER';
    case STUDENT = 'ROLE_STUDENT';
    case PARENT = 'ROLE_PARENT';
}
