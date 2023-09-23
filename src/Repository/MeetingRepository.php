<?php

namespace App\Repository;

use App\Entity\Meeting;
use Doctrine\ORM\EntityManagerInterface;

class MeetingRepository implements MeetingRepositoryInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function update(Meeting $meeting): void
    {
        $this->entityManager->persist($meeting);
        $this->entityManager->flush();
    }

    public function add(Meeting $newMeeting): void
    {
        $this->entityManager->persist($newMeeting);
        $this->entityManager->flush();
    }

    public function get(string $meetingId): Meeting
    {
        return $this->entityManager->getRepository(Meeting::class)->find($meetingId);
    }

    public function findByName(string $name): Meeting
    {
        return $this->entityManager->getRepository(Meeting::class)->findOneBy(['name' => $name]);
    }

    public function findAll()
    {
        return $this->entityManager->getRepository(Meeting::class)->findAll();
    }
}
