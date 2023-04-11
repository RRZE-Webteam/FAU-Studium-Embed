<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Application;

use ArrayAccess;
use WP_Http;

final class RemoteResponse
{
    private function __construct(
        private array $parsedJson,
        private int $statusCode,
        private ArrayAccess|array $headers = [],
    ) {
    }

    public static function new(
        array $parsedJson,
        int $statusCode,
        ArrayAccess|array $headers = [],
    ): self {

        return new self(
            $parsedJson,
            $statusCode,
            $headers,
        );
    }

    public function data(): array
    {
        return $this->parsedJson;
    }

    public function get(string $key): mixed
    {
        return $this->parsedJson[$key] ?? null;
    }

    public function headers(): ArrayAccess|array
    {
        return $this->headers;
    }

    public function header(string $key): ?string
    {
        return $this->headers[$key] ? (string) $this->headers[$key] : null;
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function success(): bool
    {
        return in_array($this->statusCode, [WP_Http::OK, WP_Http::CREATED], true);
    }
}
