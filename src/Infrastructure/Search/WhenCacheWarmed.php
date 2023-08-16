<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Search;

use Fau\DegreeProgram\Common\Application\Event\CacheWarmed;
use Fau\DegreeProgram\Common\Application\Queue\MessageBus;

final class WhenCacheWarmed
{
    public function __construct(
        private MessageBus $messageBus,
    ) {
    }

    public function scheduleSearchableContentUpdating(CacheWarmed $event): void
    {
        $this->messageBus->dispatch(
            UpdateSearchableContentMessage::new(
                $event->isFully(),
                $event->ids()
            ),
        );
    }

    public function scheduleFilterableTermsUpdating(CacheWarmed $event): void
    {
        $this->messageBus->dispatch(
            UpdateFilterableTermsMessage::new(
                $event->isFully(),
                $event->ids()
            ),
        );
    }
}
