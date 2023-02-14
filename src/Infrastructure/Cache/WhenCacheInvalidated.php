<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Cache;

use Fau\DegreeProgram\Common\Application\Event\CacheInvalidated;

final class WhenCacheInvalidated
{
    public function scheduleWarming(CacheInvalidated $event): void
    {
        wp_schedule_single_event(
            time(),
            WhenWarmingToBeStarted::HOOK,
            [$event->isFully(), $event->ids()]
        );
    }
}
