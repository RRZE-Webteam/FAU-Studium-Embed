<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Block;

final class BlockRegistry
{
    public function __construct(
        private string $blockDirectory
    ) {
    }

    public function register(Block ...$blocks): void
    {
        foreach ($blocks as $block) {
            register_block_type_from_metadata(
                $this->metadataPath($block->name()),
                [
                    'render_callback' => [$block, 'render'],
                ]
            );
        }
    }

    private function metadataPath(string $blockName): string
    {
        $blockNameWithoutNamespace = str_replace('fau/', '', $blockName);

        return trailingslashit($this->blockDirectory) . $blockNameWithoutNamespace;
    }
}
