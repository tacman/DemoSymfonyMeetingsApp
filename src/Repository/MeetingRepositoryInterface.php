<?php

namespace App\Repository;

use App\Entity\Meeting;

interface MeetingRepositoryInterface
{
    public function update(Meeting $meeting): void;

    public function add(Meeting $newMeeting): void;

    public function get(string $meetingId): Meeting;

    public function findByName(string $name): Meeting;
}
