<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Rewrite;

class InjectLanguageQueryVariable
{
    public const LANGUAGE_QUERY_VAR = 'fau-language';

    /**
     * @wp-hook query_vars
     * @param array $queryVars
     * @return array
     */
    public function inject(array $queryVars): array
    {
        $queryVars[] = self::LANGUAGE_QUERY_VAR;
        return $queryVars;
    }
}
