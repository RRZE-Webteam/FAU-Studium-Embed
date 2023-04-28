<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Embed;

use Fau\DegreeProgram\Output\Infrastructure\ApiClient\ApiClient;

final class OembedProvidersFilter
{
    /**
     * @wp-hook oembed_providers
     */
    public function addProvidingWebsite(array $providers): array
    {

        $regexp = sprintf(
            '#%s/.*#i',
            preg_quote(ApiClient::apiHost(), '#')
        );

        $oembedUrl = sprintf('%s/wp-json/oembed/1.0/embed', ApiClient::apiHost());

        $providers[$regexp] = [$oembedUrl, true];

        return $providers;
    }
}
