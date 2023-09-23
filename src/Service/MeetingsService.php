<?php

namespace App\Service;

use App\Entity\Meeting;
use App\Repository\MeetingRepositoryInterface;
use App\Repository\UserRepositoryInterface;

class MeetingsService
{
    public function __construct(
        private readonly MeetingRepositoryInterface $meetingRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function get(string $id): Meeting
    {
        return $this->meetingRepository->get($id);
    }

    public function getMeetingStatus(string $meetingId): string
    {
        return $this->get($meetingId)->status()->value;
    }

    public function rateMeeting(string $meetingId, string $userId, int $rate): void
    {
        $meeting = $this->meetingRepository->get($meetingId);
        $user = $this->userRepository->get($userId);

        $meeting->rate($user, $rate);

        $this->meetingRepository->update($meeting);
    }
}
