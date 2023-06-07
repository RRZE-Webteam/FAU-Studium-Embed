<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Repository;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;
use WP_Post;

class CurrentViewRepository
{
    public function __construct(
        private DegreeProgramViewRepository $degreeProgramViewRepository,
        private CurrentRequest $currentRequest,
    ) {
    }

    public function findTranslatedView(int|null|WP_Post $post = null): ?DegreeProgramViewTranslated
    {
        $post = get_post($post);
        if (!$post instanceof WP_Post) {
            return null;
        }

        if ($post->post_type !== DegreeProgramPostType::KEY) {
            return null;
        }

        $languageCode = $this->currentRequest->languageCode();

        /** @var array<string, null|DegreeProgramViewTranslated> $cache */
        static $cache = [];
        $cacheKey = sprintf('%d_%s', $post->ID, $languageCode);
        if (array_key_exists($cacheKey, $cache)) {
            return $cache[$cacheKey];
        }

        $cache[$cacheKey] = $this->degreeProgramViewRepository->findTranslated(
            DegreeProgramId::fromInt($post->ID),
            $languageCode,
        );

        return $cache[$cacheKey];
    }
}
