<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Rewrite;

use Fau\DegreeProgram\Common\Application\Filter\AdmissionRequirementTypeFilter;
use Fau\DegreeProgram\Common\Application\Filter\AreaOfStudyFilter;
use Fau\DegreeProgram\Common\Application\Filter\AttributeFilter;
use Fau\DegreeProgram\Common\Application\Filter\DegreeFilter;
use Fau\DegreeProgram\Common\Application\Filter\FacultyFilter;
use Fau\DegreeProgram\Common\Application\Filter\SearchKeywordFilter;
use Fau\DegreeProgram\Common\Application\Filter\SemesterFilter;
use Fau\DegreeProgram\Common\Application\Filter\StudyLocationFilter;
use Fau\DegreeProgram\Common\Application\Filter\SubjectGroupFilter;
use Fau\DegreeProgram\Common\Application\Filter\TeachingLanguageFilter;
use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Domain\MultilingualString;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 * @psalm-import-type OrderBy from CollectionCriteria
 * phpcs:disable Inpsyde.CodeQuality.NoAccessors.NoGetter
 */
final class CurrentRequest
{
    public const SEARCH_QUERY_PARAM = SearchKeywordFilter::KEY;
    public const ORDER_BY_QUERY_PARAM = 'order_by';
    public const ORDER_QUERY_PARAM = 'order';
    public const OUTPUT_MODE_QUERY_PARAM = 'output';

    public const QUERY_PARAMS_SANITIZATION_FILTERS = [
        self::SEARCH_QUERY_PARAM => FILTER_SANITIZE_SPECIAL_CHARS,
        AreaOfStudyFilter::KEY => [
            'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
        ],
        AttributeFilter::KEY => [
            'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
        ],
        DegreeFilter::KEY => [
            'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
        ],
        FacultyFilter::KEY => [
            'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
        ],
        SemesterFilter::KEY => [
            'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
        ],
        StudyLocationFilter::KEY => [
            'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
        ],
        SubjectGroupFilter::KEY => [
            'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
        ],
        TeachingLanguageFilter::KEY => [
            'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
        ],
        AdmissionRequirementTypeFilter::KEY => [
            'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
        ],
        'order_by' => FILTER_SANITIZE_SPECIAL_CHARS,
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
     * @psalm-template TParam of string
     * @psalm-param array<TParam> $params
     * @param mixed|null $default
     * @return array<TParam, mixed>
     */
    public function getParams(array $params, mixed $default = null): array
    {
        $result = [];

        foreach ($params as $param) {
            $result[$param] =  $this->queryStrings()[$param] ?? $default;
        }

        return $result;
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

    /**
     * @return OrderBy
     */
    public function orderBy(): array
    {
        $orderBy = (string) filter_input(
            INPUT_GET,
            self::ORDER_BY_QUERY_PARAM,
            FILTER_SANITIZE_SPECIAL_CHARS
        );
        $order = filter_input(
            INPUT_GET,
            self::ORDER_QUERY_PARAM,
            FILTER_SANITIZE_SPECIAL_CHARS
        );

        if (!in_array($orderBy, CollectionCriteria::SORTABLE_PROPERTIES, true)) {
            return [];
        }

        return [$orderBy => $order === 'asc' ? 'asc' : 'desc'];
    }

    public function outputMode(): string
    {
        return (string) filter_input(
            INPUT_GET,
            self::OUTPUT_MODE_QUERY_PARAM,
            FILTER_SANITIZE_SPECIAL_CHARS
        );
    }
}
