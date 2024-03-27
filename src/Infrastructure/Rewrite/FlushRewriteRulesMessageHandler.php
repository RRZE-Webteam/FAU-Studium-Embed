<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Rewrite;

use Psr\Log\LoggerInterface;

final class FlushRewriteRulesMessageHandler
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(): void
    {
        flush_rewrite_rules();

        $this->logger->info('Rewrite rules flushed.');
    }
}
