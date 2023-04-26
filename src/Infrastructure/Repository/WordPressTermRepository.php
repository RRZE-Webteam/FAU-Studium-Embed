<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Repository;

use WP_Term;
use WP_Term_Query;

final class WordPressTermRepository
{
    public function findTermId(string $termIdentifier, string $taxonomy): ?int
    {
        return $this->maybeTermIdById((int) $termIdentifier, $taxonomy)
            ?? $this->maybeTermIdByName($termIdentifier, $taxonomy)
            ?? $this->maybeTermIdBySlug($termIdentifier, $taxonomy);
    }

    public function findTermById(int $termId, string $taxonomy): ?WP_Term
    {
        $term = get_term_by('id', $termId, $taxonomy);

        return $term instanceof WP_Term ? $term : null;
    }

    /**
     * @psalm-return WP_Term[]
     */
    public function findTerms(string $taxonomy): array
    {
        /** @var WP_Term[] $terms */
        $terms = (
            new WP_Term_Query(
                [
                    'taxonomy' => $taxonomy,
                    'hierarchical' => true,
                ]
            )
        )->get_terms();

        return $terms;
    }

    private function maybeTermIdById(int $id, string $taxonomy): ?int
    {
        $term = $this->findTermById($id, $taxonomy);

        if (!$term instanceof WP_Term) {
            return null;
        }

        return $term->term_id;
    }

    private function maybeTermIdByName(string $name, string $taxonomy): ?int
    {
        $term = get_term_by('name', $name, $taxonomy);

        if (!$term instanceof WP_Term) {
            return null;
        }

        return $term->term_id;
    }

    private function maybeTermIdBySlug(string $slug, string $taxonomy): ?int
    {
        $term = get_term_by('slug', $slug, $taxonomy);

        if (!$term instanceof WP_Term) {
            return null;
        }

        return $term->term_id;
    }
}
