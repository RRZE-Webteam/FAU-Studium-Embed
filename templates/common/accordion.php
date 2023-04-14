<?php

declare(strict_types=1);

use Fau\DegreeProgram\Output\Infrastructure\Component\Component;

use function Fau\DegreeProgram\Output\renderComponent;

/**
 * @var array{innerComponents: array<Component>} $data
 */

$innerContent = renderComponent(...$data['innerComponents']);

if (!$innerContent) {
    return;
}

?>

<ul class="c-accordion">
    <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    <?= $innerContent ?>
</ul>
