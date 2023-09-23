<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Meeting;
use App\Entity\User;
use App\Repository\MeetingRepositoryInterface;
use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

class MeetingsContext implements Context
{
    private string $meetingName;
    private ?Response $meetingResponse = null;
    private ?Response $meetingStatusResponse = null;
    private string $status;

    public function __construct(
        private readonly MeetingRepositoryInterface $meetingRepository,
        private readonly KernelInterface $kernel
    ) {
    }

    /**
     * @When /^the app sends a request for meeting with name "([^"]*)"$/
     */
    public function theAppSendsARequestForMeetingWithName(string $name): void
    {
        $this->meetingName = $name;

        try {
            $meeting = $this->meetingRepository->findByName($name);
        } catch (\Exception) {
            throw new \InvalidArgumentException('There is no meeting with name: ' . $name);
        }

        $this->meetingResponse = $this->kernel->handle(Request::create("/meetings/{$meeting->id}", 'GET'));
    }

    /**
     * @Then /^the json response should be received with data for the meeting$/
     */
    public function theJsonResponseShouldBeReceivedWithDataForMeetingWithName(): void
    {
        if ($this->meetingResponse === null) {
            throw new \RuntimeException('No response received');
        }

        $decoded = json_decode($this->meetingResponse->getContent(), true, JSON_THROW_ON_ERROR);
        Assert::same($decoded['name'], $this->meetingName);
    }

    /**
     * @Given /^the app has a meeting with fewer than "([^"]*)" participants and with name "([^"]*)", but didn't start yet$/
     */
    public function theAppHasAMeetingWithFewerThanParticipantsAndWithNameButDidnTStartYet(int $count, string $name): void
    {
        $meeting = new Meeting($name, new \DateTimeImmutable('+1 day'));
        for ($i = 0; $i < $count - 1; $i++) {
            $meeting->addAParticipant(new User('Some User'));
        }

        $this->meetingRepository->add($meeting);
    }

    /**
     * @Given /^the app has a meeting with "([^"]*)" participants and with name "([^"]*)", but didn't start yet$/
     */
    public function theAppHasAMeetingWithParticipantsAndWithNameButDidnTStartYet(int $count, string $name): void
    {
        $meeting = new Meeting($name, new \DateTimeImmutable('+1 day'));
        for ($i = 0; $i < $count; $i++) {
            $meeting->addAParticipant(new User('Some User'));
        }

        $this->meetingRepository->add($meeting);
    }

    /**
     * @Given /^the app has a meeting with name "([^"]*)", started but didn't finish$/
     */
    public function theAppHasAMeetingWithNameStartedButDidnTFinish(string $name): void
    {
        $this->meetingRepository->add(new Meeting($name, new \DateTimeImmutable('-30 minutes')));
    }

    /**
     * @Given /^the app has a meeting with name "([^"]*)", finished$/
     */
    public function theAppHasAMeetingWithNameFinished(string $name): void
    {
        $this->meetingRepository->add(new Meeting($name, new \DateTimeImmutable('-1 day')));
    }

    /**
     * @Given /^the app sends a request for the meeting status$/
     */
    public function theAppSendsARequestForMeetingStatusForMeetingWithName(): void
    {
        try {
            $meeting = $this->meetingRepository->findByName($this->meetingName);
        } catch (\Exception) {
            throw new \InvalidArgumentException('There is no meeting with name: ' . $this->meetingName);
        }

        $this->meetingStatusResponse = $this->kernel->handle(Request::create("/meetings/{$meeting->id}/status", 'GET'));    }

    /**
     * @Given /^the json response should be received with status for the meeting$/
     */
    public function theJsonResponseShouldBeReceivedWithStatusForMeetingWithName(): void
    {
        if ($this->meetingStatusResponse === null) {
            throw new \RuntimeException('No response received');
        }

        $decoded = json_decode($this->meetingStatusResponse->getContent(), true, JSON_THROW_ON_ERROR);
        Assert::keyExists($decoded, 'status');

        $this->status = $decoded['status'];
    }

    /**
     * @Given /^the meeting should be available$/
     */
    public function theMeetingShouldBeAvailable(): void
    {
        if ($this->status !== 'open to registration') {
            throw new \RuntimeException('Meeting is not available');
        }
    }

    /**
     * @Given /^the meeting should not be available$/
     */
    public function theMeetingShouldNotBeAvailable(): void
    {
        if ($this->status === 'open to registration') {
            throw new \RuntimeException('Meeting is available');
        }
    }
}
