<?php

namespace App\Tests\PhpUnit\Unit\Service;

use App\Entity\Status;
use App\Service\MeetingsService;
use App\Tests\PhpUnit\Mother\MeetingMother;
use App\Tests\PhpUnit\Mother\RateMother;
use App\Tests\PhpUnit\Mother\UserMother;
use App\Tests\PhpUnit\TestDouble\Stub\MeetingRepositoryStub;
use App\Tests\PhpUnit\TestDouble\Stub\UserRepositoryStub;
use PHPUnit\Framework\TestCase;

class MeetingServiceUnitTest extends TestCase
{
    public function testGet(): void
    {
        $meetingRepository = new MeetingRepositoryStub();
        $meetingRepository->add($meeting = MeetingMother::sample());

        $service = new MeetingsService($meetingRepository, new UserRepositoryStub());
        $foundMeeting = $service->get($meeting->id);

        $this->assertSame($meeting, $foundMeeting);
    }

    public function testGetMeetingStatus(): void
    {
        $meetingRepository = new MeetingRepositoryStub();
        $meetingRepository->add($meeting = MeetingMother::finished());

        $service = new MeetingsService($meetingRepository, new UserRepositoryStub());
        $status = $service->getMeetingStatus($meeting->id);

        $this->assertEquals(Status::DONE->value, $status);
    }

    public function testRateMeeting(): void
    {
        $userRepository = new UserRepositoryStub();
        $userRepository->add($user = UserMother::random());

        $meeting = MeetingMother::finished();
        $meeting->addAParticipant($user);
        $meetingRepository = new MeetingRepositoryStub();
        $meetingRepository->add($meeting);

        $service = new MeetingsService($meetingRepository, $userRepository);
        $service->rateMeeting($meeting->id, $user->id, $rate = RateMother::inRange());

        $this->assertTrue($meetingRepository->isUpdateCalledWith($meeting));
        $this->assertEquals($rate, $meetingRepository->get($meeting->id)->getUserRate($user));
    }
}
