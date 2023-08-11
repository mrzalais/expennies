<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entity\User;
use App\Services\UserProviderService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Slim\Views\Twig;

class VerifyController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly UserProviderService $userProviderService
    ) {
    }

    public function index(ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render($response, 'auth/verify.twig');
    }

    public function verify(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        /** @var User $user */
        $user = $request->getAttribute('user');

        if (! hash_equals((string) $user->getId(), $args['id']) || ! hash_equals(sha1($user->getEmail()), $args['hash'])) {
            throw new RuntimeException('Verification failed');
        }

        if (!$user->getVerifiedAt()) {
            $this->userProviderService->verifyUser($user);
        }

        return $response->withHeader('Location', '/')->withStatus(302);
    }
}
