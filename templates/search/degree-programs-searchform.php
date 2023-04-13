<?php

declare(strict_types=1);

use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     searchQuery: string,
 * } $data
 * @var array $data
 */

[
    'searchQuery' => $searchQuery,
] = $data;

?>

<form
    class="c-degree-programs-sarchform"
    action="<?= esc_url((string) get_permalink((int) get_the_id())) ?>"
    method="get"
>
    <div class="c-degree-programs-sarchform__inner">
        <?= renderComponent(
            new Component(
                Icon::class,
                ['name' => 'search', 'className' => 'c-degree-programs-sarchform__search-icon']
            )
        ) ?>

        <input
            type="search"
            name="s"
            value="<?= esc_attr($searchQuery) ?>"
            class="c-degree-programs-sarchform__input"
            placeholder="<?= esc_attr_x(
                'Please enter search term...',
                'frontoffice: degree programs search form',
                'fau-degree-program-output'
            ) ?>"
        />
        <button class="c-degree-programs-sarchform__submit" type="submit">
            <span class="label">
                <?= esc_html_x(
                    'Search',
                    'frontoffice: degree programs search form',
                    'fau-degree-program-output'
                ) ?>
            </span>
            <?= renderComponent(
                new Component(
                    Icon::class,
                    ['name' => 'search']
                )
            ) ?>
        </button>
    </div>
</form>
