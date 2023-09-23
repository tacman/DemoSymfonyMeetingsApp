<?php

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function get(string $userId): User;
}
