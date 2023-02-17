<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Search;

final class UpdateSearchableContentMessageHandler
{
    public function __construct(private SearchableContentUpdater $contentUpdater)
    {
    }

    public function __invoke(UpdateSearchableContentMessage $message): void
    {
        if ($message->isFully()) {
            $this->contentUpdater->updateFully();
            return;
        }

        $this->contentUpdater->updatePartially($message->ids());
    }
}
