<?php

namespace App\Tests\PhpUnit\Mother;

use App\Entity\User;

class UserMother
{
    public static function random(): User
    {
        return new User('Some User');
    }
}
