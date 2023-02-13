<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Repository;

use ArrayObject;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;

/**
 * @template T of DegreeProgramViewRaw | DegreeProgramViewTranslated
 * @template-extends ArrayObject<array-key, T>
 * @template-implements PaginationAwareCollection<T>
 */
class WordpressApiPaginationAwareCollection extends ArrayObject implements PaginationAwareCollection
{
    /**
     * @param T ...$items
     */
    public function __construct(
        private int $totalPages,
        private int $totalItems,
        private int $currentPage,
        DegreeProgramViewRaw | DegreeProgramViewTranslated ...$items
    ) {

        parent::__construct($items);
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    public function nextPage(): ?int
    {
        if ($this->currentPage() >= $this->maxPages()) {
            return null;
        }

        return ++$this->currentPage() ;
    }

    public function previousPage(): ?int
    {
        if ($this->currentPage() <= 1) {
            return null;
        }

        return min((--$this->currentPage()), $this->maxPages());
    }

    public function maxPages(): int
    {
        return $this->totalPages;
    }

    public function totalItems(): int
    {
        return $this->totalItems;
    }
}
