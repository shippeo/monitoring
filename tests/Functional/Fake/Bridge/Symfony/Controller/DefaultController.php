<?php

declare(strict_types=1);

namespace Functional\Fake\Bridge\Symfony\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController
{
    #[Route(path: '/', name: 'index')]
    public function indexAction(): Response
    {
        return new Response();
    }
}
