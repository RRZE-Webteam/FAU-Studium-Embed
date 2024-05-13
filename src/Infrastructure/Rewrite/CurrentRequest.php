<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Rewrite;

use Fau\DegreeProgram\Common\Application\Filter\AdmissionRequirementTypeFilter;
use Fau\DegreeProgram\Common\Application\Filter\AreaOfStudyFilter;
use Fau\DegreeProgram\Common\Application\Filter\AttributeFilter;
use Fau\DegreeProgram\Common\Application\Filter\DegreeFilter;
use Fau\DegreeProgram\Common\Application\Filter\FacultyFilter;
use Fau\DegreeProgram\Common\Application\Filter\GermanLanguageSkillsForInternationalStudentsFilter;
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

    private const ARRAY_OF_IDS = [
        'filter' => FILTER_VALIDATE_INT,
        'flags' => FILTER_REQUIRE_ARRAY,
        'options' => ['min_range' => 1],
    ];

    private const ARRAY_OF_STRING = [
        'filter' => FILTER_SANITIZE_SPECIAL_CHARS,
        'flags' => FILTER_REQUIRE_ARRAY,
    ];

    public const QUERY_PARAMS_SANITIZATION_FILTERS = [
        self::SEARCH_QUERY_PARAM => FILTER_SANITIZE_SPECIAL_CHARS,
        AreaOfStudyFilter::KEY => self::ARRAY_OF_IDS,
        AttributeFilter::KEY => self::ARRAY_OF_IDS,
        DegreeFilter::KEY => self::ARRAY_OF_IDS,
        FacultyFilter::KEY => self::ARRAY_OF_IDS,
        GermanLanguageSkillsForInternationalStudentsFilter::KEY => self::ARRAY_OF_IDS,
        SemesterFilter::KEY => self::ARRAY_OF_IDS,
        StudyLocationFilter::KEY => self::ARRAY_OF_IDS,
        SubjectGroupFilter::KEY => self::ARRAY_OF_IDS,
        TeachingLanguageFilter::KEY => self::ARRAY_OF_IDS,
        AdmissionRequirementTypeFilter::KEY => self::ARRAY_OF_STRING,
        'order_by' => FILTER_SANITIZE_SPECIAL_CHARS,
        'order' => FILTER_SANITIZE_SPECIAL_CHARS,
        'output' => FILTER_SANITIZE_SPECIAL_CHARS,
    ];

    /**
     * @return LanguageCodes
     */
    public function languageCode(): string
    {
        $languageCode = explode('_', get_locale())[0] ?? '';
        return in_array($languageCode, [MultilingualString::DE, MultilingualString::EN], true)
            ? $languageCode
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
