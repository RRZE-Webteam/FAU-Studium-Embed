<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\StructuredData;

use JsonException;
use JsonSerializable;

final class ScriptBuilder
{
    public function build(array|JsonSerializable $data): string
    {
        try {
            return sprintf(
                '<script type="application/ld+json">%s</script>' . "\n",
                json_encode($data, JSON_THROW_ON_ERROR)
            );
        } catch (JsonException) {
            return '';
        }
    }
}
