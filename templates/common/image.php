<?php

declare(strict_types=1);

/**
 * @var array{html: string} $data
 */

[
    'html' => $html,
] = $data;

if (!$html) {
    return;
}

echo str_replace( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    '<img',
    '<img alt=""',
    wp_kses(
        $html,
        [
            'img' => [
                'width' => true,
                'height' => true,
                'src' => true,
                'class' => true,
                'decoding' => true,
                'loading' => true,
                'srcset' => true,
                'sizes' => true,
            ],
        ]
    )
);
