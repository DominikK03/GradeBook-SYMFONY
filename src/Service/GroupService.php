<?php

namespace App\Service;

use App\DataFixtures\GroupFixtures;

final class GroupService
{

    public static function getRandomGroupForFixture(): string
    {
        $groups = array_keys(GroupFixtures::GROUPS);
        shuffle($groups);
        return array_pop($groups);
    }

}
