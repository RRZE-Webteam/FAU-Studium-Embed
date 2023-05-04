<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Application\StructuredData;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use JsonSerializable;

final class Course implements JsonSerializable
{
    private function __construct(
        private string $name,
        private string $description,
        private Organization $provider,
    ) {
    }

    public static function fromTranslatedView(
        DegreeProgramViewTranslated $view
    ): self {

        return new self(
            $view->title(),
            $view->metaDescription(),
            Organization::fau(),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => $this->name,
            'description' => $this->description,
            'provider' => $this->provider,
        ];
    }
}
