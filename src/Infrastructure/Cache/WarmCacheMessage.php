<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Cache;

use Fau\DegreeProgram\Common\Application\Queue\Message;

final class WarmCacheMessage implements Message
{
    /**
     * @param array<int> $ids
     */
    private function __construct(
        private bool $isFully,
        private array $ids,
    ) {
    }

    /**
     * @param array<int> $ids
     */
    public static function new(
        bool $isFully,
        array $ids,
    ): self {

        return new self($isFully, $ids);
    }

    public function isFully(): bool
    {
        return $this->isFully;
    }

    /**
     * @return array<int>
     */
    public function ids(): array
    {
        return $this->ids;
    }

    public static function fromArray(array $array): static
    {
        /** @var array{is_fully: bool, ids: array<int>} $array */
        return new self($array['is_fully'], $array['ids']);
    }

    public function jsonSerialize(): array
    {
        return [
            'is_fully' => $this->isFully,
            'ids' => $this->ids,
        ];
    }
}
