<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Repository\BilingualRepository;
use Fau\DegreeProgram\Output\Application\OriginalDegreeProgramView;
use Fau\DegreeProgram\Output\Application\OriginalDegreeProgramViewRepository;
use Fau\DegreeProgram\Output\Infrastructure\Cache\PostDegreeProgramCache;
use Fau\DegreeProgram\Output\Infrastructure\Environment\EnvironmentDetector;

final class WordPressDatabaseOriginalDegreeProgramViewRepository implements OriginalDegreeProgramViewRepository
{
    public function __construct(
        private EnvironmentDetector $environmentDetector
    ) {
    }

    public function find(DegreeProgramId $degreeProgramId): ?OriginalDegreeProgramView
    {
        if ($this->environmentDetector->isProvidingWebsite()) {
            return null;
        }

        $postId = $degreeProgramId->asInt();

        return new OriginalDegreeProgramView(
            (int) get_post_meta($postId, PostDegreeProgramCache::ORIGINAL_ID_KEY, true),
            MultilingualString::fromTranslations(
                "post_meta:{$postId}:original_link",
                (string) get_post_meta(
                    $postId,
                    PostDegreeProgramCache::ORIGINAL_LINK_KEY,
                    true
                ),
                (string) get_post_meta(
                    $postId,
                    BilingualRepository::addEnglishSuffix(PostDegreeProgramCache::ORIGINAL_LINK_KEY),
                    true
                ),
            )
        );
    }
}
