<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Rewrite;

use Fau\DegreeProgram\Common\Domain\MultilingualString;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 */
final class CurrentRequest
{
    /**
     * @return LanguageCodes
     */
    public function languageCode(): string
    {
        $queriedLanguage = (string) get_query_var(
            InjectLanguageQueryVariable::LANGUAGE_QUERY_VAR,
        );

        return in_array(
            $queriedLanguage,
            [MultilingualString::DE, MultilingualString::EN],
            true
        )
            ? $queriedLanguage
            : MultilingualString::DE;
    }
}
