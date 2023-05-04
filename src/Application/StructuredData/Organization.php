<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Application\StructuredData;

use JsonSerializable;

final class Organization implements JsonSerializable
{
    private function __construct(
        private string $name,
        private string $sameAs,
    ) {
    }

    public static function fau(): self
    {
        return new self(
            'Friedrich-Alexander-Universität Erlangen-Nürnberg',
            'https://www.fau.de/'
        );
    }

    public function jsonSerialize(): array
    {
        return [
            '@type' => 'Organization',
            'name' => $this->name,
            'sameAs' => $this->sameAs,
        ];
    }
}
