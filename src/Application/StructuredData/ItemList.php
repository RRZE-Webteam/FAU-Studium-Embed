<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Application\StructuredData;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use JsonSerializable;

final class ItemList implements JsonSerializable
{
    /**
     * @param list<string> $links
     */
    private function __construct(
        private array $links
    ) {
    }

    /**
     * @psalm-param PaginationAwareCollection<DegreeProgramViewTranslated> $collection
     */
    public static function fromViewCollection(
        PaginationAwareCollection $collection,
    ): self {

        $links = [];
        foreach ($collection as $item) {
            /** @var DegreeProgramViewTranslated $item */
            $links[] = $item->link();
        }

        return new self($links);
    }

    public function jsonSerialize(): array
    {
        $itemListElement = [];
        foreach ($this->links as $i => $link) {
            $itemListElement[] = [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'url' => $link,
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'itemListElement' => $itemListElement,
        ];
    }
}
