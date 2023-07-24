<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\DegreeProgramDetail;
use Fau\DegreeProgram\Output\Infrastructure\Component\Link;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 */

[
    'view' => $view,
] = $data

?>

<section class="c-degree-program-short" lang="<?= esc_attr(get_bloginfo('language')) ?>">
    <div class="c-degree-program-short__grid">
        <h1 class="c-degree-program-short__title">
            <?= esc_html($view->title()) ?>
            (<?= esc_html($view->degree()->abbreviation()) ?>)
        </h1>
        <div class="c-degree-program-short__description">
            <?= wp_kses_post($view->entryText()) ?>
        </div>
        <dl class="c-details">
            <?= renderComponent(
                new Component(
                    DegreeProgramDetail::class,
                    [
                        'icon' => 'degree',
                        'term' => _x(
                            'Degree',
                            'frontoffice: single view',
                            'fau-degree-program-output'
                        ),
                        'description' => $view->degree()->name(),
                    ]
                ),
                new Component(
                    DegreeProgramDetail::class,
                    [
                        'icon' => 'standard-duration',
                        'term' => _x(
                            'Duration of studies in semester',
                            'frontoffice: single view',
                            'fau-degree-program-output'
                        ),
                        'description' => $view->standardDuration(),
                    ]
                ),
            ) ?>
        </dl>
        <div class="c-degree-program-short__link">
            <?= renderComponent(
                new Component(
                    Link::class,
                    [
                        'url' => $view->url(),
                        'text' => _x(
                            'Read more',
                            'frontoffice: shortcode link',
                            'fau-degree-program-output'
                        ),
                        'type' => Link::DARK,
                    ]
                )
            ) ?>
        </div>
    </div>
</section>
