<?php

declare(strict_types=1);

use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Icon;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @psalm-var array{
 *     searchQuery: string,
 *     queryStrings: array<string, string>,
 * } $data
 * @var array $data
 */

[
    'searchQuery' => $searchQuery,
    'queryStrings' => $queryStrings,
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
            name="<?= esc_attr(CurrentRequest::SEARCH_QUERY_PARAM) ?>"
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

    <?php foreach ($queryStrings as $key => $value) : ?>
        <?php if ($key !== 'keyword') : ?>
            <input type="hidden" name="<?= esc_attr($key) ?>" value="<?= esc_attr($value) ?>" />
        <?php endif; ?>
    <?php endforeach; ?>
</form>
