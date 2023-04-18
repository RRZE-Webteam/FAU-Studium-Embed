<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Rewrite;

use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\MultilingualString;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 */
final class CurrentRequest
{
    public const SEARCH_QUERY_PARAM = 'keyword';
    public const QUERY_PARAMS_SANITIZATION_FILTERS = [
        self::SEARCH_QUERY_PARAM => FILTER_SANITIZE_SPECIAL_CHARS,
        DegreeProgram::DEGREE => [
            'filter' => FILTER_SANITIZE_NUMBER_INT,
            'flags' => FILTER_REQUIRE_ARRAY,
        ],
        DegreeProgram::LOCATION => [
            'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
            'flags' => FILTER_REQUIRE_ARRAY,
        ],
        DegreeProgram::ADMISSION_REQUIREMENTS => [
            'filter' => FILTER_SANITIZE_NUMBER_INT,
            'flags' => FILTER_REQUIRE_ARRAY,
        ],
        // TODO: Filters list is incomplete
        'orderby' => FILTER_SANITIZE_SPECIAL_CHARS,
        'order' => FILTER_SANITIZE_SPECIAL_CHARS,
        'output' => FILTER_SANITIZE_SPECIAL_CHARS,
    ];

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

    public function searchKeyword(): string
    {
        $keyword = $this->queryStrings()[self::SEARCH_QUERY_PARAM] ?? '';
        return esc_attr((string) $keyword);
    }

    public function queryStrings(): array
    {
        $queryStrings = (array) filter_input_array(
            INPUT_GET,
            self::QUERY_PARAMS_SANITIZATION_FILTERS,
            false
        );

        return $queryStrings;
    }

    /**
     * @return array<string, string>
     */
    public function flattenedQueryStrings(): array
    {
        return $this->flattenQueryStringsArray($this->queryStrings());
    }

    /**
     * @return array<string, string>
     */
    private function flattenQueryStringsArray(array $array, string $prefix = ''): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix ? $prefix . '[' . (string) $key . ']' : (string) $key;

            if (is_array($value)) {
                $result = array_merge($result, $this->flattenQueryStringsArray($value, $newKey));
                continue;
            }

            $result[$newKey] = (string) $value;
        }

        return $result;
    }
}
