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

        if (!function_exists('is_plugin_active')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $result = is_plugin_active('fau-degree-program/fau-degree-program.php');

        return $result;
    }
}
