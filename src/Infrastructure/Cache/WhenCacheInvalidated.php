<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Cache;

use Fau\DegreeProgram\Common\Application\Event\CacheInvalidated;
use Fau\DegreeProgram\Common\Application\Queue\MessageBus;

final class WhenCacheInvalidated
{
    public function __construct(private MessageBus $messageBus)
    {
    }

    public function scheduleWarming(CacheInvalidated $event): void
    {
        $this->messageBus->dispatch(
            WarmCacheMessage::new($event->isFully(), $event->ids())
        );
    }
}
