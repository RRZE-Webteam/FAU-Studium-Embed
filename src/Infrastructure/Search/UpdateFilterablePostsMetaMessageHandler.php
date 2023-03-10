<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Search;

final class UpdateFilterablePostsMetaMessageHandler
{
    public function __construct(private FilterablePostsMetaUpdater $filterablePostsMetaUpdater)
    {
    }

    public function __invoke(UpdateSearchableContentMessage $message): void
    {
        if ($message->isFully()) {
            $this->filterablePostsMetaUpdater->updateFully();
            return;
        }

        $this->filterablePostsMetaUpdater->updatePartially($message->ids());
    }
}
