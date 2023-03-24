<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;

/**
 * @var array{view: DegreeProgramViewTranslated} $data
 */

[
    'view' => $view,
] = $data

?>

<div class="c-single-degree-program c-single-degree-program--full">
    <h1 class="c-single-degree-program__title"><?= esc_html($view->title()) ?></h1>

    <ul class="c-single-degree-program__content">
        <?php foreach ($view->content()->asArray() as $item) : ?>
            <?php ['title' => $title, 'description' => $description] = $item ?>
            <li>
                <h4><?= esc_html($title) ?></h4>
                <div><?= wp_kses_post($description) ?></div>
            </li>
        <?php endforeach ?>
    </ul>
</div>
