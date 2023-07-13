<?php

declare(strict_types=1);

namespace App;

use App\Contracts\SessionInterface;
use App\DataObjects\SessionConfig;
use App\Exception\SessionException;

class Session implements SessionInterface
{
    public function __construct(private readonly SessionConfig $options)
    {
    }

    public function start(): void
    {
        if ($this->isActive()) {
            throw new SessionException('Session has already been started');
        }

        if (headers_sent($fileName, $line)) {
            throw new SessionException('Headers already sent');
        }

        session_set_cookie_params([
            'secure' => $this->secure ?? true,
            'httponly' => $this->httponly ?? true,
            'samesite' => $this->samesite ?? 'lax',
        ]);

        if (!empty($this->options->name)) {
            session_name($this->options->name);
        }

        if (!session_start()) {
            throw new SessionException('Unable to start the session');
        }
    }

    public function save(): void
    {
        session_write_close();
    }

    public function isActive(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }
}
