<?php

namespace App\Tests\PhpUnit\Mother;

use App\Entity\Meeting;

class RateMother
{
    public static function inRange(): int
    {
        return rand(Meeting::MIN_RATE, Meeting::MAX_RATE);
    }

    public static function outOfARange(): int
    {
        return rand(Meeting::MAX_RATE + 1, 999);
    }
}
