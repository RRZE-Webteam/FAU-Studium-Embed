<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Psr\Log\LoggerInterface;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 * @psalm-type SingleDegreeProgramAttributes = array{
 *     id: int,
 *     lang: LanguageCodes,
 *     include: array<string>,
 *     exclude: array<string>,
 *     format: 'full' | 'short',
 * }
 *
 * @TODO: include and exclude functionality could be handled in different ways.
 *      Most probably array item should be value of domain model constants
 *      (i.e. title, subtitle, standard_duration etc.).
 *      The first option is to make our view model aware about included and excluded fields
 *      so the model getters can return empty value if field should be outputted.
 *      The second option is to provide standalone value object having ability to detect
 *      is the field visible.
 *      Or we could expose array from view model, filter values, and recreate view model
 *      from filtered array.
 *      Maybe the first option is better...
 */
final class SingleDegreeProgram implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'id' => 0,
        'lang' => MultilingualString::DE,
        'include' => [],
        'exclude' => [],
        'format' => 'full',
    ];

    public function __construct(
        private Renderer $renderer,
        private DegreeProgramViewRepository $degreeProgramViewRepository,
        private LoggerInterface $logger,
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

        return $this->renderer->render(
            'single-degree-program-' . $attributes['format'],
            ['view' => $view]
        );
    }
}
