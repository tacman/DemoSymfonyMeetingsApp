<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {

        return new Response('<h1>Hello</h1>');
    }
}
