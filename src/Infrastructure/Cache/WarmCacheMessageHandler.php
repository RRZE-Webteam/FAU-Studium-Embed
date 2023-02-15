<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Cache;

use Fau\DegreeProgram\Common\Application\Cache\CacheWarmer;
use Psr\SimpleCache\InvalidArgumentException;

final class WarmCacheMessageHandler
{
    public function __construct(private CacheWarmer $cacheWarmer)
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(WarmCacheMessage $message): void
    {
        if ($message->isFully()) {
            $this->cacheWarmer->warmFully();
            return;
        }

        $this->cacheWarmer->warmPartially($message->ids());
    }
}
