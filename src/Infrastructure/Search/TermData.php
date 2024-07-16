<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Search;

use Fau\DegreeProgram\Common\Domain\MultilingualString;

/**
 * @psalm-type TermData = array{
 *     taxonomy: string,
 *     name: MultilingualString,
 *     slug: string,
 *     term_id: int,
 *     remote_term_id: int,
 *     parent_term_id: int
 * }
 */
final class TermData
{
    private function __construct(
        private string $taxonomy,
        private MultilingualString $name,
        private string $slug,
        private int $termId,
        private int $remoteTermId,
        private int $parentTermId
    ) {
    }

    /**
     * @psalm-param TermData $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['taxonomy'],
            $data['name'],
            $data['slug'],
            $data['term_id'],
            $data['remote_term_id'],
            $data['parent_term_id'],
        );
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function termId(): int
    {
        return $this->termId;
    }

    public function remoteTermId(): int
    {
        return $this->remoteTermId;
    }

    public function taxonomy(): string
    {
        return $this->taxonomy;
    }

    public function name(): MultilingualString
    {
        return $this->name;
    }

    public function parentTermId(): int
    {
        return $this->parentTermId;
    }
}
