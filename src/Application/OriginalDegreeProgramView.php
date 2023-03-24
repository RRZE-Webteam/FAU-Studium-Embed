<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Application;

use Fau\DegreeProgram\Common\Domain\MultilingualString;

final class OriginalDegreeProgramView
{
    public function __construct(
        private int $originalId,
        private MultilingualString $originalLink,
    ) {
    }

    public function originalId(): int
    {
        return $this->originalId;
    }

    public function originalLink(): MultilingualString
    {
        return $this->originalLink;
    }
}
