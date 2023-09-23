<?php

namespace App\Services;

use App\Entity\Meeting;
use App\Repository\MeetingRepository;

class MeetingsService
{
    public function __construct(private readonly MeetingRepository $meetingRepository)
    {}

    public function get(string $id): Meeting
    {
        return $this->meetingRepository->get($id);
    }
}
