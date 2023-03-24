<?php

declare(strict_types=1);

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;

/**
 * @psalm-var array{
 *     collection: PaginationAwareCollection<DegreeProgramViewTranslated>,
 *     filters: array<string, array<int>>,
 *     output: 'list' | 'tiles',
 * } $data
 * @var array $data
 */

[
    'collection' => $collection,
    'filters' => $show,
    'output' => $output,
] = $data

?>

<div class="c-degree-programs-search-form">
    <h1 class="c-degree-programs-search-form__title">
        <?= esc_html_x(
            'Degree programs',
            'frontoffice: degree programs search form',
            'fau-degree-program-output'
        ) ?>
    </h1>

    <ul class="c-degree-programs-search-form__filters">
        <?php foreach ($show as $filter => $preselectedValues) : ?>
            <li class="search-form-filter">
                <div class="search-form-filter__label">
                    <?= esc_html($filter) ?>
                </div>
            </li>
        <?php endforeach; ?>
        <li class="search-form-filter search-form-filter--output">
            <div class="search-form-filter__label">
                <?= esc_html_x(
                    'Layout',
                    'frontoffice: degree programs search form',
                    'fau-degree-program-output'
                ) ?>
            </div>
            <div class="search-form-filter__options">
                <?= esc_html('list/tiles') ?>
            </div>
        </li>
    </ul>

    <ul class="c-degree-programs-search-form__result">
        <?php foreach ($collection as $view) : ?>
            <?php /** @var DegreeProgramViewTranslated $view */ ?>
            <li class="degree-program-preview">
                <a class="degree-program-preview__link"
                   href="<?= esc_url($view->link()) ?>">
                    <img class="degree-program-preview__teaser-image"
                         src="<?= esc_url($view->teaserImage()->url()) ?>"
                         alt="<?= esc_attr($view->title()) ?>">
                    <div class="degree-program-preview__title">
                        <?= esc_html($view->title()) ?>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
