<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Meeting;
use App\Entity\User;
use App\Repository\MeetingRepositoryInterface;
use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

class RateMeetingsContext implements Context
{
    private Meeting $meeting;
    private User $user;

    public function __construct(
        private readonly MeetingRepositoryInterface $meetingRepository,
        private readonly KernelInterface $kernel
    ) {
    }

    /**
     * @Given /^the app has a finished meeting$/
     */
    public function theAppHasAFinishedMeeting(): void
    {
        $this->meetingRepository->add($this->meeting = new Meeting('Sample', new \DateTimeImmutable('-1 day')));
    }

    /**
     * @Given /^the app has a user which is a participant of the meeting$/
     */
    public function theAppHasAUserWhichIsAParticipantOfTheMeeting(): void
    {
        $this->meeting->addAParticipant($this->user = new User('Sample'));
        $this->meetingRepository->update($this->meeting);
    }

    /**
     * @When /^the app sends a request to rate the meeting by the user with rate "([^"]*)"$/
     */
    public function theAppSendsARequestToRateTheMeetingByTheUserWithNameAndRate(int $rate): void
    {
        $response = $this->kernel->handle(Request::create(
            "/meetings/{$this->meeting->id}/rate",
            'POST',
            [
                'user_id' => $this->user->id,
                'rate' => $rate
            ]
        ));

        $decoded = json_decode($response->getContent(), true, JSON_THROW_ON_ERROR);
        Assert::true($decoded['success']);
    }

    /**
     * @Then /^the meeting should be rated with rate "([^"]*)"$/
     */
    public function theMeetingShouldBeRated(int $rate): void
    {
        $meeting = $this->meetingRepository->get($this->meeting->id);
        Assert::same($rate, $meeting->getUserRate($this->user));
    }
}
