<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Rewrite;

use Fau\DegreeProgram\Common\Application\Queue\Message;

final class FlushRewriteRulesMessage implements Message
{
    private function __construct()
    {
    }

    public static function new(): self
    {
        return new self();
    }

    public static function fromArray(array $array): static
    {
        return new self();
    }

    public function jsonSerialize(): array
    {
        return [];
    }
}
