<?php

declare(strict_types=1);

use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @var array{icon: string, term: string, description: string} $data
 */

[
    'icon' => $icon,
    'term' => $term,
    'description' => $description,
] = $data;

?>

<div class="c-detail">
    <?= renderComponent(
        new Component(
            Icon::class,
            ['name' => $icon]
        )
    ) ?>
    <dt><?= esc_html($term) ?></dt>
    <dd>
        <?= wp_kses( // Could be string, link or list of links
            $description,
            ['a' => ['href' => true]]
        ) ?>
    </dd>
</div>
