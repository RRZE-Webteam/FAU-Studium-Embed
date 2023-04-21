<?php

declare(strict_types=1);

use Fau\DegreeProgram\Output\Infrastructure\Component\Component;
use Fau\DegreeProgram\Output\Infrastructure\Component\Link;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\ReferrerUrlHelper;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @var array{
 *     referrerUrlHelper: ReferrerUrlHelper
 * } $data
 */

[
    'referrerUrlHelper' => $referrerUrlHelper,
] = $data;

$backUrl = $referrerUrlHelper->backUrl();

if (!$backUrl) {
    return;
}
?>

<div class="c-single-degree-program__back l-container">
    <?= renderComponent(
        new Component(
            Link::class,
            [
                'url' => $backUrl,
                'text' => _x(
                    'Back to degree programs',
                    'frontoffice: single view',
                    'fau-degree-program-output'
                ),
                'icon' => 'back',
            ]
        )
    ) ?>
</div>
