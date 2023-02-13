<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Application;

use Requests_Utility_CaseInsensitiveDictionary;
use WP_Http;

final class RemoteResponse
{
    /**
     * @param Requests_Utility_CaseInsensitiveDictionary|array $headers
     */
    private function __construct(
        private array $parsedJson,
        private int $statusCode,
        private iterable $headers = [],
    ) {
    }

    /**
     * @param Requests_Utility_CaseInsensitiveDictionary|array $headers
     */
    public static function new(
        array $parsedJson,
        int $statusCode,
        iterable $headers = [],
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

    /**
     * @return Requests_Utility_CaseInsensitiveDictionary|array
     */
    public function headers(): iterable
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
