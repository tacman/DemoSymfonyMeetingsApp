<?php

namespace App\Controller;

use App\Service\MeetingsService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webmozart\Assert\Assert;

final class MeetingsController
{
    public function __construct(private readonly MeetingsService $meetingsService)
    {
    }

    #[Route('/meetings/{id}', name: 'meeting')]
    public function meeting(string $id): Response
    {
        return new JsonResponse($this->meetingsService->get($id));
    }

    #[Route('/meetings/{id}/status', name: 'meeting_status')]
    public function meetingStatus(string $id): Response
    {
        return new JsonResponse(['status' => $this->meetingsService->getMeetingStatus($id)]);
    }

    #[Route('/meetings/{id}/rate', name: 'meeting_rate', methods: ['POST'])]
    public function meetingRate(string $id, Request $request): Response
    {
        $userId = $request->request->get('user_id');
        Assert::notNull($userId, 'user_id can not be null');
        $rate = $request->request->getInt('rate');
        Assert::notNull($userId, 'rate can not be null');

        $this->meetingsService->rateMeeting($id, $userId, $rate);

        return new JsonResponse(['success' => true]);
    }
}
