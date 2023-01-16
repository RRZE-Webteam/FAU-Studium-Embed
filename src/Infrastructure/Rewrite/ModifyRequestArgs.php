<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Rewrite;

use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Output\Infrastructure\Repository\PostsRepository;
use Inpsyde\WpContext;
use WP_Post;

class ModifyRequestArgs
{
    public function __construct(private PostsRepository $postsRepository)
    {
    }

    /**
     * @wp-hook request
     * @param array $queryVars
     * @return array
     */
    public function modify(array $queryVars): array
    {
        if (! $this->shouldIntercept($queryVars)) {
            return $queryVars;
        }

        $germanPost = $this->postsRepository->findByGermanSlug((string) $queryVars['name']);
        if ($germanPost instanceof WP_Post) {
            // Given slug corresponds to a German post, so nothing to do here
            $queryVars[InjectLanguageQueryVariable::LANGUAGE_QUERY_VAR] = MultilingualString::DE;
            return $queryVars;
        }

        $englishPost = $this->postsRepository->findByEnglishSlug((string) $queryVars['name']);
        if (! $englishPost instanceof WP_Post) {
            return ['error' => '404'];
        }

        $queryVars[InjectLanguageQueryVariable::LANGUAGE_QUERY_VAR] = MultilingualString::EN;
        $queryVars[DegreeProgramPostType::KEY] = $englishPost->post_name;
        $queryVars['name'] = $englishPost->post_name;
        return $queryVars;
    }

    private function shouldIntercept(array $queryVars): bool
    {
        $context = WpContext::determine();
        return $context->isFrontoffice() && array_key_exists('post_type', $queryVars) &&
            $queryVars['post_type'] === DegreeProgramPostType::KEY;
    }
}
