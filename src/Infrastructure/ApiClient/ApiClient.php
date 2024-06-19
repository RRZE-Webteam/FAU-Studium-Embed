<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\ApiClient;

use Fau\DegreeProgram\Output\Application\RemoteResponse;
use JsonException;
use Psr\Log\LoggerInterface;
use WP_Error;

final class ApiClient
{
    private const API_HOST = 'https://meinstudium.fau.de';

    private array $defaultArgs;

    public function __construct(
        private LoggerInterface $logger
    ) {

        $this->defaultArgs = [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'timeout' => 15,
        ];
    }

    public function get(string $path, array $queryArgs = [], array $args = []): ?RemoteResponse
    {
        $requestUrl = add_query_arg($queryArgs, self::normalizeRequestUrl($path));

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
                $this->defaultArgs
            )
        );

        if ($response instanceof WP_Error) {
            $this->logger->error(
                sprintf(
                    'ApiClient: Fetching data failed with: %s.',
                    $response->get_error_message()
                )
            );

            return null;
        }

        $parsedResponse = $this->prepareResponse($response);
        if (!$parsedResponse) {
            return null;
        }

        if (!$parsedResponse->success()) {
            $this->logger->error(
                (string) $parsedResponse->get('message'),
                [
                    'data' => $parsedResponse->get('data'),
                ]
            );

            return null;
        }

        return $parsedResponse;
    }

    private function prepareResponse(array $response): ?RemoteResponse
    {
        try {
            /**
             * @var array<string, mixed> $responseBody
             */
            $responseBody = json_decode(wp_remote_retrieve_body($response), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            $this->logger->error(
                'ApiClient: Malformed data.',
                [
                    'exception' => $exception,
                ]
            );

            return null;
        }

        $responseHeaders = wp_remote_retrieve_headers($response);
        $responseCode = (int) wp_remote_retrieve_response_code($response);

        return RemoteResponse::new(
            $responseBody,
            $responseCode,
            $responseHeaders,
        );
    }

    public static function apiHost(): string
    {
        if (defined('FAU_DEGREE_PROGRAM_API_HOST')) {
            return (string) FAU_DEGREE_PROGRAM_API_HOST;
        }

        return getenv('FAU_DEGREE_PROGRAM_API_HOST') ?: self::API_HOST;
    }

    private static function normalizeRequestUrl(string $path): string
    {
        return (string) preg_replace(
            '#(?<!:)//#',
            '/',
            self::apiHost() . '/wp-json/' . $path
        );
    }
}
