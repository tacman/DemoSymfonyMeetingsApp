<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: '`meetings`')]
class Meeting
{
    const PARTICIPANTS_LIMIT = 5;
    const MIN_RATE = 1;
    const MAX_RATE = 5;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "NONE")]
    #[ORM\Column]
    public readonly string $id;

    #[ORM\Column(length: 255)]
    public readonly string $name;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public readonly \DateTimeImmutable $startTime;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    public readonly \DateTimeImmutable $endTime;

    #[ORM\ManyToMany(targetEntity: User::class, cascade: ['persist'])]
    private Collection $participants;

    #[ORM\Column(type: Types::JSON)]
    private array $rates = [];

    public function __construct(string $name, \DateTimeImmutable $startTime)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->startTime = $startTime;
        $this->endTime = $startTime->add(\DateInterval::createFromDateString('1 hour'));
        $this->participants = new ArrayCollection();
    }

    public function participatesBy(User $user): bool
    {
        return $this->participants->contains($user);
    }

    public function rate(User $user, int $rate): void
    {
        Assert::range(
            $rate,
            self::MIN_RATE,
            self::MAX_RATE,
            sprintf("Rate must be between %d - %d. Given: %d", self::MIN_RATE, self::MAX_RATE, $rate)
        );
        Assert::true($this->isFinished(), "Can't rate not finished meeting.");
        Assert::true($this->participatesBy($user), "User {$user->id} not participating to meeting with id {$this->id}");
        Assert::keyNotExists($this->rates, $user->id, "User {$user->id} already rated meeting with id {$this->id}");

        $this->rates[$user->id] = $rate;
    }

    public function getUserRate(User $user): int
    {
        Assert::keyExists($this->rates, $user->id, "User {$user->id} not rated meeting with id {$this->id}");

        return $this->rates[$user->id];
    }

    public function addAParticipant(User $participant): void
    {
        Assert::lessThan(
            $this->participants->count(),
            self::PARTICIPANTS_LIMIT,
            "Participants limit exceeded for meeting with id {$this->id}"
        );

        $this->participants->add($participant);
    }

    public function status(): Status
    {
        if ($this->participants->count() === self::PARTICIPANTS_LIMIT && !$this->isStarted()) {
            return Status::FULL;
        }

        if ($this->isStarted() && !$this->isFinished()) {
            return Status::IN_SESSION;
        }

        if ($this->isFinished()) {
            return Status::DONE;
        }

        return Status::OPEN_TO_REGISTRATION;
    }

    private function isStarted(): bool
    {
        return $this->startTime <= new \DateTimeImmutable('now');
    }

    private function isFinished(): bool
    {
        return $this->endTime < new \DateTimeImmutable('now');
    }
}
