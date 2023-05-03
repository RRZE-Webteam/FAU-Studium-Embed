<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Template;

final class ImageSizeRegistrar
{
    private const FEATURED_IMAGE_SIZE = 'fau-featured';
    private const TEASER_IMAGE_SIZE = 'fau-teaser';

    /**
     * @wp-hook after_setup_theme
     */
    public function registerImageSizes(): void
    {
        add_image_size(self::FEATURED_IMAGE_SIZE, 1400, 350);
        add_image_size(self::TEASER_IMAGE_SIZE, 300, 300, true);
    }

    /**
     * @wp-hook fau.degree-program.image-size
     */
    public function filterImageSize(string $size, string $type): string
    {
        return match ($type) {
            'featured_image' => self::FEATURED_IMAGE_SIZE,
            'teaser_image' => self::TEASER_IMAGE_SIZE,
            default => $size,
        };
    }
}
