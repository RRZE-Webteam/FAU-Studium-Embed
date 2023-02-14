<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Cache;

use Fau\DegreeProgram\Common\Application\Cache\CacheWarmer;
use Psr\SimpleCache\InvalidArgumentException;

final class WhenWarmingToBeStarted
{
    public const HOOK = 'warm_degree_program_cache';

    public function __construct(private CacheWarmer $cacheWarmer)
    {
    }

    /**
     * @psalm-param array<int> $ids
     *
     * @throws InvalidArgumentException
     */
    public function warm(bool $isFull, array $ids): void
    {
        if ($isFull) {
            $this->cacheWarmer->warmFully();
            return;
        }

        $this->cacheWarmer->warmPartially($ids);
    }
}
