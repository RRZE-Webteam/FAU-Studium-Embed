<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Block;

class CategoryRegistry
{
    /**
     * @wp-hook block_categories_all
     */
    public function register(array $blockCategories): array
    {
        $blockCategories[] = [
            'slug' => 'fau',
            'title' => _x(
                'FAU',
                'backoffice: block editor category',
                'fau-degree-program-output'
            ),
        ];

        return $blockCategories;
    }
}
