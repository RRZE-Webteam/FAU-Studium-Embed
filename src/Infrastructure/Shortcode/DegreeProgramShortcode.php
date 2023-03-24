<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Shortcode;

use Fau\DegreeProgram\Output\Infrastructure\Component\ComponentFactory;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramCombinations;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramsSearchForm;
use Fau\DegreeProgram\Output\Infrastructure\Component\RenderableComponent;
use Fau\DegreeProgram\Output\Infrastructure\Component\SingleDegreeProgram;
use Psr\Log\LoggerInterface;

final class DegreeProgramShortcode
{
    public const KEY = 'fau-studium';
    private const DISPLAY_ATTRIBUTE = 'display';

    private const COMPONENTS_MAP = [
        'searchform' => DegreeProgramsSearchForm::class,
        'degree-program' => SingleDegreeProgram::class,
        'combinations' => DegreeProgramCombinations::class,
    ];

    public function __construct(
        private ComponentFactory $componentFactory,
        private ShortcodeAttributesNormalizer $attributesNormalizer,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @param array<string, mixed>|string $attributes
     *       Empty string if no attributes were provided
     * @return string
     */
    public function render(array|string $attributes): string
    {
        $attributes = is_array($attributes) ? $attributes : [];
        $display = (string) ($attributes[self::DISPLAY_ATTRIBUTE] ?? '');

        if (!isset(self::COMPONENTS_MAP[$display])) {
            $this->logger->warning(
                sprintf(
                    // phpcs:ignore Inpsyde.CodeQuality.LineLength.TooLong
                    'Invalid "%s" attribute for "%s" shortcode: "%s" provided, one from "%s" is allowed.',
                    self::DISPLAY_ATTRIBUTE,
                    self::KEY,
                    $display,
                    implode(', ', array_keys(self::COMPONENTS_MAP)),
                ),
                [
                    'post_id' => get_the_ID(),
                ]
            );
            return '';
        }

        $component = $this->componentFactory->makeComponent(self::COMPONENTS_MAP[$display]);
        if (!$component instanceof RenderableComponent) {
            return '';
        }

        $attributes = $this->attributesNormalizer->normalize(
            $component::class,
            $attributes
        );

        return $component->render($attributes);
    }
}
