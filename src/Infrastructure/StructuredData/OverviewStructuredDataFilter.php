<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\StructuredData;

use Fau\DegreeProgram\Output\Application\StructuredData\ItemList;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramsSearch;
use Fau\DegreeProgram\Output\Infrastructure\Shortcode\DegreeProgramShortcode;
use Fau\DegreeProgram\Output\Infrastructure\Shortcode\ShortcodeAttributesNormalizer;
use Fau\DegreeProgram\Output\Infrastructure\Shortcode\ShortcodeAttributesParser;
use WP_Post;

/**
 * @psalm-import-type DegreeProgramsSearchAttributes from DegreeProgramsSearch
 */
final class OverviewStructuredDataFilter
{
    public function __construct(
        private ShortcodeAttributesParser $shortcodeAttributesParser,
        private ShortcodeAttributesNormalizer $normalizer,
        private DegreeProgramsSearch $overviewComponent,
        private ScriptBuilder $scriptBuilder,
    ) {
    }

    /**
     * @wp-hook the_seo_framework_ldjson_scripts
     */
    public function outputStructuredData(string $output, int $objectId): string
    {
        $post = get_post($objectId);
        if (!$post instanceof WP_Post) {
            return $output;
        }

        $parsedAttributes = $this->shortcodeAttributesParser->parseShortcodeAttributes(
            $post->post_content,
            DegreeProgramShortcode::KEY
        );
        $display = $parsedAttributes[DegreeProgramShortcode::DISPLAY_ATTRIBUTE] ?? '';

        if ($display !== 'search') {
            return $output;
        }

        $normalizedAttributes = $this->normalizer->normalize(
            DegreeProgramsSearch::class,
            $parsedAttributes,
        );

        /** @var DegreeProgramsSearchAttributes $attributes */
        $attributes = wp_parse_args(
            $normalizedAttributes,
            DegreeProgramsSearch::DEFAULT_ATTRIBUTES
        );

        $collection = $this->overviewComponent->findCollection(
            $attributes
        );

        if (!$collection) {
            return $output;
        }

        return $output
            . $this->scriptBuilder->build(
                ItemList::fromViewCollection($collection)
            );
    }
}
