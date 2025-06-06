<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Search;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Psr\Log\LoggerInterface;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 */
final class SearchableContentUpdater
{
    public const SEARCHABLE_CONTENT_KEY = 'fau_degree_program_searchable_content';
    public const SEARCHABLE_CONTENT_EXTENDED_KEY = 'fau_degree_program_searchable_content_extended';

    public function __construct(
        private DegreeProgramCollectionRepository $degreeProgramCollectionRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function updateFully(): void
    {
        $criteria = CollectionCriteria::new()
            ->withoutPagination();

        $this->update($criteria);

        $this->logger->info('Degree program searchable content updated fully.');
    }

    /**
     * @param array<int> $ids
     */
    public function updatePartially(array $ids): void
    {
        $criteria = CollectionCriteria::new()
            ->withoutPagination()
            ->withInclude($ids);

        $this->update($criteria);

        $this->logger->info(
            sprintf(
                'Degree program searchable content updated for IDs: %s.',
                implode(', ', $ids)
            )
        );
    }

    private function update(CollectionCriteria $criteria): void
    {
        $rawCollection = $this->degreeProgramCollectionRepository->findRawCollection($criteria);
        if (!$rawCollection instanceof PaginationAwareCollection) {
            return;
        }

        /** @var DegreeProgramViewRaw $rawView */
        foreach ($rawCollection as $rawView) {
            foreach (MultilingualString::LANGUAGES as $code => $name) {
                update_post_meta(
                    $rawView->id()->asInt(),
                    self::SEARCHABLE_CONTENT_KEY . '_' . $code,
                    $this->buildSearchableContent($rawView, $code)
                );
                update_post_meta(
                    $rawView->id()->asInt(),
                    self::SEARCHABLE_CONTENT_EXTENDED_KEY . '_' . $code,
                    $this->buildSearchableContentExtended($rawView, $code)
                );
            }
        }
    }

    /**
     * @psalm-param LanguageCodes $languageCode
     */
    private function buildSearchableContent(
        DegreeProgramViewRaw $rawView,
        string $languageCode
    ): string {

        $parts = [
            $rawView->title()->asString($languageCode),
            $rawView->subtitle()->asString($languageCode),
            ...array_values($rawView->keywords()->asArrayOfStrings($languageCode)->getArrayCopy()),
        ];

        return implode(' ', $parts);
    }

    /**
     * @psalm-param LanguageCodes $languageCode
     */
    private function buildSearchableContentExtended(
        DegreeProgramViewRaw $rawView,
        string $languageCode
    ): string {

        $parts = [
            $rawView->content()->about()->description()->asString($languageCode),
        ];

        return implode(' ', $parts);
    }
}
