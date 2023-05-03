<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Application\DegreeProgramViewPropertiesFilter;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\ReferrerUrlHelper;
use Psr\Log\LoggerInterface;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 * @psalm-type SingleDegreeProgramAttributes = array{
 *     id: int,
 *     lang: LanguageCodes,
 *     include: array<string>,
 *     exclude: array<string>,
 *     format: 'full' | 'short',
 *     className: string,
 * }
 */
final class SingleDegreeProgram implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'id' => 0,
        'lang' => MultilingualString::DE,
        'include' => [],
        'exclude' => [],
        'format' => 'full',
        'className' => '',
    ];

    public function __construct(
        private Renderer $renderer,
        private DegreeProgramViewRepository $degreeProgramViewRepository,
        private LoggerInterface $logger,
        private ReferrerUrlHelper $referrerUrlHelper,
        private DegreeProgramViewPropertiesFilter $degreeProgramViewPropertiesFilter,
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        /** @var SingleDegreeProgramAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);
        if (!$attributes['id']) {
            $this->logger->warning(
                'It is not possible to render single degree program without ID.',
                [
                    'post_id' => get_the_ID(),
                ]
            );

            return '';
        }

        $view = $this->degreeProgramViewRepository->findTranslated(
            DegreeProgramId::fromInt($attributes['id']),
            $attributes['lang']
        );

        if (!$view instanceof DegreeProgramViewTranslated) {
            return '';
        }

        $view = $this->filterViewProperties(
            $view,
            $attributes['include'],
            $attributes['exclude'],
        );

        return $this->renderer->render(
            'single-degree-program-' . $attributes['format'],
            [
                'view' => $view,
                'referrerUrlHelper' => $this->referrerUrlHelper,
                'className' => $attributes['className'],
            ]
        );
    }

    /**
     * @param array<string> $include
     * @param array<string> $exclude
     */
    private function filterViewProperties(
        DegreeProgramViewTranslated $view,
        array $include,
        array $exclude
    ): DegreeProgramViewTranslated {

        if ($include) {
            return $this->degreeProgramViewPropertiesFilter->include($view, $include);
        }

        if ($exclude) {
            return $this->degreeProgramViewPropertiesFilter->exclude($view, $exclude);
        }

        return $view;
    }
}
