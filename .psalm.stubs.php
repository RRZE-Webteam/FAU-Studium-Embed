<?php

declare(strict_types=1);

namespace Psr\Container {
    interface ContainerInterface
    {
        /** @param string|class-string $name */
        public function has(string $name): bool;

        /**
         * @template T of object
         * @psalm-param string|class-string<T> $name
         * @psalm-return (
         *     $name is class-string
         *     ? T
         *     : ($name is \Inpsyde\Modularity\Package::PROPERTIES
         *        ? \Inpsyde\Modularity\Properties\PluginProperties
         *        : mixed)
         * )
         */
        public function get(string $name): object;
    }
}
