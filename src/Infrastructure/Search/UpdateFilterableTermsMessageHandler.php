<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Search;

final class UpdateFilterableTermsMessageHandler
{
    public function __construct(private FilterableTermsUpdater $filterableTermsUpdater)
    {
    }

    public function __invoke(UpdateFilterableTermsMessage $message): void
    {
        if ($message->isFully()) {
            $this->filterableTermsUpdater->updateFully();
            return;
        }

        $this->filterableTermsUpdater->updatePartially($message->ids());
    }
}
