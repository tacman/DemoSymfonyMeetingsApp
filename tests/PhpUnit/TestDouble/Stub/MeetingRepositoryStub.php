<?php

namespace App\Tests\PhpUnit\TestDouble\Stub;

use App\Entity\Meeting;
use App\Repository\MeetingRepositoryInterface;
use App\Tests\PhpUnit\Mother\MeetingMother;

class MeetingRepositoryStub implements MeetingRepositoryInterface
{
    /**
     * @var Meeting[]
     */
    private array $meetings;
    private ?Meeting $updatedMeeting = null;

    public function update(Meeting $meeting): void
    {
        $this->updatedMeeting = $meeting;
        $this->meetings[$meeting->id] = $meeting;
    }

    public function isUpdateCalledWith(Meeting $meeting): bool
    {
        return $this->updatedMeeting === $meeting;
    }

    public function add(Meeting $newMeeting): void
    {
        $this->meetings[$newMeeting->id] = $newMeeting;
    }

    public function get(string $meetingId): Meeting
    {
        return $this->meetings[$meetingId];
    }

    public function findByName(string $name): Meeting
    {
        return MeetingMother::withName($name);
    }
}
