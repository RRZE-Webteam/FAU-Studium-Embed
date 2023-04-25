<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Application;

use RuntimeException;

final class ArrayPropertiesAccessor
{
    public function get(array $array, string $path): mixed
    {
        if (!$path) {
            return $array;
        }

        $parts = explode('.', $path);
        $result = $array;
        foreach ($parts as $part) {
            if (!is_array($result) || !isset($result[$part])) {
                throw new RuntimeException('Path does not exist.');
            }

            $result = $result[$part];
        }

        return $result;
    }

    /**
     * Method does not allow to replace scalar value with array.
     */
    public function set(array &$array, string $path, mixed $value): void
    {
        if (!$path) {
            return;
        }

        $parts = explode('.', $path);
        $item = &$array;
        foreach ($parts as $part) {
            if (!is_array($item)) {
                return;
            }

            $item = &$item[$part];
        }

        $item = $value;
    }
}
