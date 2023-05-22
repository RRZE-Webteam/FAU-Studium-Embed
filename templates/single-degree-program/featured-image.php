<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 * @var Renderer $renderer
 */

[
    'view' => $view,
] = $data;

if (!$view->featuredImage()->rendered()) {
    return;
}

?>

<?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<div class="c-single-degree-program__featured-image">
    <?= $renderer->render(
        'common/image',
        [
            'html' => $view->featuredImage()->rendered(),
        ]
    ) ?>
</div>
<?php // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
