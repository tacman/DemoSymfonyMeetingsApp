<?php

namespace App\Tests\PhpUnit\TestDouble\Stub;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;

class UserRepositoryStub implements UserRepositoryInterface
{
    /**
     * @var User[]
     */
    private array $users;

    public function get(string $userId): User
    {
        return $this->users[$userId];
    }

    public function add(User $user): void
    {
        $this->users[$user->id] = $user;
    }
}
