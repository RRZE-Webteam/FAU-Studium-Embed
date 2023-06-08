<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Rewrite;

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

        $degreeProgramPost = $this->postsRepository->findByGermanSlug((string) $queryVars['name']);
        if ($degreeProgramPost instanceof WP_Post) {
            // Given slug corresponds to a core post name
            return $queryVars;
        }

        $degreeProgramPost = $this->postsRepository->findByEnglishSlug((string) $queryVars['name']);
        if (! $degreeProgramPost instanceof WP_Post) {
            return ['error' => '404'];
        }

        $queryVars[DegreeProgramPostType::KEY] = $degreeProgramPost->post_name;
        $queryVars['name'] = $degreeProgramPost->post_name;
        return $queryVars;
    }

    private function shouldIntercept(array $queryVars): bool
    {
        $context = WpContext::determine();
        return $context->isFrontoffice()
            && array_key_exists('post_type', $queryVars)
            && $queryVars['post_type'] === DegreeProgramPostType::KEY
            && isset($queryVars['name']);
    }
}
