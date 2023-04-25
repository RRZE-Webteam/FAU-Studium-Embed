<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Shortcode;

use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\SemesterTaxonomy;
use Fau\DegreeProgram\Common\Infrastructure\Content\Taxonomy\TeachingLanguageTaxonomy;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramCombinations;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramsSearch;
use Fau\DegreeProgram\Output\Infrastructure\Component\RenderableComponent;
use Fau\DegreeProgram\Output\Infrastructure\Component\SingleDegreeProgram;

final class ShortcodeAttributesNormalizer
{
    /**
     * @var array<string, callable(array<string, mixed>): array<string, mixed>>
     */
    private array $map;

    public function __construct()
    {
        $this->map = [
            DegreeProgramsSearch::class => [$this, 'search'],
            SingleDegreeProgram::class => [$this, 'single'],
            DegreeProgramCombinations::class => [$this, 'combinations'],
        ];
    }

    /**
     * @param class-string<RenderableComponent> $componentId
     * @param array<string, mixed> $rawAttributes
     * @return array<string, mixed>
     */
    public function normalize(string $componentId, array $rawAttributes): array
    {
        $callable = $this->map[$componentId] ?? null;
        if (!$callable) {
            return $rawAttributes;
        }

        $normalizedAttributes = $callable($rawAttributes);

        return $this->common($normalizedAttributes);
    }

    /**
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    private function common(array $attributes): array
    {
        $lang = (string) ($attributes['lang'] ?? MultilingualString::DE);

        if (!isset(MultilingualString::LANGUAGES[$lang])) {
            $attributes['lang'] = MultilingualString::DE;
        }

        return $attributes;
    }

    /**
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    private function search(array $attributes): array
    {
        // TODO: create a value object
        $listOfSupportedFilters = [
            SemesterTaxonomy::REST_BASE,
            TeachingLanguageTaxonomy::REST_BASE,
            // TODO: ... list is incomplete
        ];

        /** @var array<string> $visibleFilters */
        $visibleFilters = wp_parse_list((string) ($attributes['filters'] ?? ''));
        $attributes['filters'] = [];

        $filters = [];
        foreach ($listOfSupportedFilters as $filter) {
            if (isset($attributes[$filter]) && is_string($attributes[$filter])) {
                // @TODO check, is it possible to use hyphen for shortcode attribute name?
                // Editor uses [fau-studium faculty="NAT faculty name, Phil faculty name" teaching-language="German"]

                // @TODO most probably we will use term names as preselected filter values
                //       in the shortcode context. So we need the repository to convert
                //       term names list to term IDs list.
                $filters[$filter] = wp_parse_list($attributes[$filter]);
                // Preselected filter value gets priority over visible filters attribute.
                // Anyway preselected filters will be hidden.
                continue;
            }

            if (!in_array($filter, $visibleFilters, true)) {
                continue;
            }

            // Editor uses [fau-studium filters="faculty,teaching-language"]
            $filters[$filter] = [];
        }

        $attributes['filters'] = $filters;

        return $attributes;
    }

    /**
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    private function single(array $attributes): array
    {
        $attributes['id'] = (int) ($attributes['id'] ?? 0);
        $attributes['include'] = wp_parse_list((string) ($attributes['include'] ?? ''));
        $attributes['exclude'] = wp_parse_list((string) ($attributes['exclude'] ?? ''));

        return $attributes;
    }

    /**
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    private function combinations(array $attributes): array
    {
        $attributes['faculty'] = wp_parse_id_list((string) ($attributes['faculty'] ?? ''));
        $attributes['degree'] = wp_parse_id_list((string) ($attributes['degree'] ?? ''));

        return $attributes;
    }
}
