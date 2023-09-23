<?php

namespace App\Tests\PhpUnit\Unit\Entity;

use App\Entity\Meeting;
use App\Tests\PhpUnit\Mother\MeetingMother;
use App\Tests\PhpUnit\Mother\RateMother;
use App\Tests\PhpUnit\Mother\UserMother;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class MeetingUnitTest extends TestCase
{
    public function testMeetingRatingUserAlreadyRated(): void
    {
        $meeting = MeetingMother::finished();
        $meeting->addAParticipant($user = UserMother::random());
        $meeting->rate($user, RateMother::inRange());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("User {$user->id} already rated meeting with id {$meeting->id}");

        $meeting->rate($user, RateMother::inRange());
    }

    public function testMeetingRatingUserNotParticipated(): void
    {
        $meeting = MeetingMother::finished();
        $user = UserMother::random();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("User {$user->id} not participating to meeting with id {$meeting->id}");

        $meeting->rate($user, RateMother::inRange());
    }

    public function testMeetingRatingNotFinishedMeeting(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Can't rate not finished meeting.");

        MeetingMother::startedNotFinished()->rate(UserMother::random(), RateMother::inRange());
    }

    public function testMeetingRatingOutOfARange(): void
    {
        $rate = RateMother::outOfARange();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf("Rate must be between %d - %d. Given: %d",  Meeting::MIN_RATE, Meeting::MAX_RATE, $rate)
        );

        MeetingMother::finished()->rate(UserMother::random(), $rate);
    }

    public function testMeetingRating(): void
    {
        $meeting = MeetingMother::finished();
        $meeting->addAParticipant($user = UserMother::random());

        $meeting->rate($user, $rate = RateMother::inRange());

        $this->assertEquals($rate, $meeting->getUserRate($user));
    }

    public function testGetMeetingStatus(): void
    {
        $meeting = MeetingMother::notFullNotStarted();
        $this->assertSame('open to registration', $meeting->status()->value);

        $meeting = MeetingMother::fullNotStarted();
        $this->assertSame('full', $meeting->status()->value);

        $meeting = MeetingMother::fullStarted();
        $this->assertSame('in session', $meeting->status()->value);
        $meeting = MeetingMother::startedNotFinished();
        $this->assertSame('in session', $meeting->status()->value);

        $meeting = MeetingMother::finished();
        $this->assertSame('done', $meeting->status()->value);
        $meeting = MeetingMother::fullFinished();
        $this->assertSame('done', $meeting->status()->value);
    }

    public function testAddParticipant(): void
    {
        $meeting = MeetingMother::sample();
        $meeting->addAParticipant($participant = UserMother::random());

        $this->assertTrue($meeting->participatesBy($participant));
    }

    public function testParticipantsNumberLimitExceeded(): void
    {
        $meeting = MeetingMother::withParticipants(5);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Participants limit exceeded for meeting with id {$meeting->id}");

        $meeting->addAParticipant(UserMother::random());
    }
}
