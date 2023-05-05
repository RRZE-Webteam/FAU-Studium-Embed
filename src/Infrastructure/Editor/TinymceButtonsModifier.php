<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Editor;

class TinymceButtonsModifier
{
    /**
     * @wp-hook mce_buttons
     * @param array $buttons
     * @return array
     */
    public function modify(array $buttons): array
    {
        $buttons[] = 'fau_degree_program_output_shortcodes';

        return $buttons;
    }
}
