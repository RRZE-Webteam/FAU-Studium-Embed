<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Rewrite;

final class ReferrerUrlHelper
{
    public const REFERRER_PARAM = 'fau-search';

    public function __construct(
        private CurrentRequest $currentRequest,
    ) {
    }

    public function addReferrerArgs(string $url): string
    {
        global $wp;
        $allowedQueryParams = $this->currentRequest->queryStrings();
        $baseUrl = home_url($wp->request);
        $safeUrl = $allowedQueryParams
            ? $baseUrl . '?' . http_build_query($allowedQueryParams)
            : $baseUrl;

        return add_query_arg([
            self::REFERRER_PARAM => rawurlencode($safeUrl),
        ], $url);
    }

    public function backUrl(): string
    {
        $encodedUrl = (string) filter_input(
            INPUT_GET,
            self::REFERRER_PARAM,
            FILTER_SANITIZE_SPECIAL_CHARS,
        );
        if (!$encodedUrl) {
            return '';
        }

        $decodedUrl = rawurldecode($encodedUrl);

        return wp_validate_redirect($decodedUrl);
    }
}
