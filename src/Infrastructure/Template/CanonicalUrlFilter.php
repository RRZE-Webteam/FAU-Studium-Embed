<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Template;

use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Output\Application\OriginalDegreeProgramView;
use Fau\DegreeProgram\Output\Application\OriginalDegreeProgramViewRepository;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;
use WP_Post;

final class CanonicalUrlFilter
{
    public function __construct(
        private OriginalDegreeProgramViewRepository $originalDegreeProgramViewRepository,
        private CurrentRequest $currentRequest,
    ) {
    }

    /**
     * @wp-hook get_canonical_url
     */
    public function filterCanonicalUrl(string $url, WP_Post $post): string
    {
        if ($post->post_type !== DegreeProgramPostType::KEY) {
            return $url;
        }

        $originalView = $this->originalDegreeProgramViewRepository->find(
            DegreeProgramId::fromInt($post->ID),
        );

        if (!$originalView instanceof OriginalDegreeProgramView) {
            return $url;
        }

        return $originalView->originalLink()->asString(
            $this->currentRequest->languageCode()
        );
    }
}
