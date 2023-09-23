<?php

namespace App\Tests\PhpUnit\Mother;

use App\Entity\Meeting;

class MeetingMother
{
    public static function withName(string $name): Meeting
    {
        return new Meeting($name, new \DateTimeImmutable('now'));
    }

    public static function sample(): Meeting
    {
        return new Meeting('Sample', new \DateTimeImmutable('now'));
    }

    public static function notFullNotStarted(): Meeting
    {
        return new Meeting('Sample', new \DateTimeImmutable('+1 day'));
    }

    public static function startedNotFinished(): Meeting
    {
        return new Meeting('Sample', new \DateTimeImmutable('-30 minutes'));
    }

    public static function withParticipants(int $participantsCount): Meeting
    {
        return self::addParticipants(self::sample(), $participantsCount);
    }

    public static function fullNotStarted(): Meeting
    {
        return self::addParticipants(self::notFullNotStarted(), Meeting::PARTICIPANTS_LIMIT);
    }
    public static function fullStarted(): Meeting
    {
        return self::addParticipants(self::startedNotFinished(), Meeting::PARTICIPANTS_LIMIT);
    }

    public static function finished(): Meeting
    {
        return new Meeting('Sample', new \DateTimeImmutable('-1 day'));
    }

    public static function fullFinished(): Meeting
    {
        return self::addParticipants(self::finished(), Meeting::PARTICIPANTS_LIMIT);
    }

    private static function addParticipants(Meeting $meeting, int $participantsCount): Meeting
    {
        for ($i = 0; $i < $participantsCount; $i++) {
            $meeting->addAParticipant(UserMother::random());
        }

        return $meeting;
    }
}
