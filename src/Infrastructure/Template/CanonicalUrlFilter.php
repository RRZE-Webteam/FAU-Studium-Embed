<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Template;

use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Output\Application\OriginalDegreeProgramView;
use Fau\DegreeProgram\Output\Application\OriginalDegreeProgramViewRepository;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\InjectLanguageQueryVariable;
use WP_Post;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 */
final class CanonicalUrlFilter
{
    public function __construct(
        private OriginalDegreeProgramViewRepository $originalDegreeProgramViewRepository,
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

        /** @var LanguageCodes $lang */
        $lang = get_query_var(
            InjectLanguageQueryVariable::LANGUAGE_QUERY_VAR,
            MultilingualString::DE
        );

        return $originalView->originalLink()->asString($lang);
    }
}
