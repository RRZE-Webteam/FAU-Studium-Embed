<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\ApiClient;

use Fau\DegreeProgram\Output\Application\RemoteResponse;
use Psr\Log\LoggerInterface;
use RuntimeException;
use WP_Error;

final class ApiClient
{
    private const ROOT_URL = 'https://meinstudium.fau.de';

    private array $headers = [];

    public function __construct(
        private LoggerInterface $logger
    ) {

        $this->headers = [
            'Accept' => 'application/json',
        ];
    }

    /**
     * @throws RuntimeException
     */
    public function get(string $path, array $queryArgs = [], array $args = []): RemoteResponse
    {
        $requestUrl = add_query_arg($queryArgs, $this->normalizeRequestUrl($path));

        $this->logger->debug(
            sprintf(
                'ApiClient: Send GET request to %s',
                $requestUrl
            )
        );

        $response = wp_remote_get(
            $requestUrl,
            wp_parse_args(
                $args,
                [
                    'headers' => $this->headers,
                ]
            )
        );

        $parsedResponse = $this->parseResponse($response);

        return RemoteResponse::new(
            $parsedResponse['parsedBody'],
            $parsedResponse['statusCode'],
            $parsedResponse['headers'],
        );
    }

    /**
     * @return array{
     *  parsedBody: array,
     *  headers: \Requests_Utility_CaseInsensitiveDictionary | array,
     *  statusCode: int,
     * }
     */
    private function parseResponse(array|WP_Error $response): array
    {
        if ($response instanceof WP_Error) {
            $this->logger->error(
                sprintf(
                    'ApiClient: Fetching data failed with: %s',
                    $response->get_error_message()
                )
            );

            throw new RuntimeException(
                sprintf(
                    'Could not fetch data from remote repository. Error message: %s',
                    $response->get_error_message()
                )
            );
        }

        /**
         * @var array<string, mixed> $responseBody
         */
        $responseBody = json_decode(wp_remote_retrieve_body($response), true, 512, JSON_THROW_ON_ERROR);

        $responseHeaders = wp_remote_retrieve_headers($response);
        $responseCode = (int) wp_remote_retrieve_response_code($response);

        $results = [
            'parsedBody' => $responseBody,
            'headers' => $responseHeaders,
            'statusCode' => $responseCode,
        ];

        return $results;
    }

    private function baseUrl(): string
    {
        /** @var ?string $baseUrl */
        $baseUrl = defined('FAU_STUDIES_API_BASE_URL')
            ? FAU_STUDIES_API_BASE_URL
            : null;

        return $baseUrl ?? (getenv('FAU_STUDIES_API_BASE_URL') ?: self::ROOT_URL);
    }

    private function normalizeRequestUrl(string $path): string
    {
        return trailingslashit($this->baseUrl()) . ltrim($path, '/');
    }
}
