<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Environment;

final class EnvironmentDetector
{
    public function isProvidingWebsite(): bool
    {
        /** @var null|bool $result */
        static $result = null;
        if (is_bool($result)) {
            return $result;
        }

        $result = function_exists('\Fau\DegreeProgram\plugin');

        return $result;
    }
}
