<?php

declare(strict_types=1);

namespace {
    class WP_CLI {
        public static function add_command(string $name, callable|object $command, array $args = []): void
        {}
    }

    /**
     * @psalm-template TArgs
     * @psalm-param TArgs $args
     * @psalm-return TArgs
     */
    function wp_parse_args(array $args, array $defaultArgs): array
    {}

    function did_filter(string $hook_name): int
    {}
}
