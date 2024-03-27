<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Rewrite;

use Fau\DegreeProgram\Common\Application\Queue\MessageBus;

final class WhenCacheWarmed
{
    public function __construct(
        private MessageBus $messageBus,
    ) {
    }

    public function scheduleFlushRewriting(): void
    {
        $this->messageBus->dispatch(
            FlushRewriteRulesMessage::new(),
        );
    }
}
