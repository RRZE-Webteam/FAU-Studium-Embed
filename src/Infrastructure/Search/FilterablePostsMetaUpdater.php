<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Search;

use Fau\DegreeProgram\Common\Application\AdmissionRequirementsTranslated;
use Fau\DegreeProgram\Common\Application\AdmissionRequirementTranslated;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Psr\Log\LoggerInterface;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 */
final class FilterablePostsMetaUpdater
{
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

        $this->logger->info('Degree program filterable post metas updated fully.');
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
                'Degree program filterable post metas updated for IDs: %s.',
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
            foreach (array_keys(MultilingualString::LANGUAGES) as $code) {
                $this->updateForLanguage($code, $rawView);
            }
        }
    }

    /**
     * @psalm-param LanguageCodes $languageCode
     */
    private function updateForLanguage(string $languageCode, DegreeProgramViewRaw $rawView): void
    {
        update_post_meta(
            $rawView->id()->asInt(),
            DegreeProgram::DEGREE . '_' . $languageCode,
            $rawView->degree()->name()->asString($languageCode)
        );

        update_post_meta(
            $rawView->id()->asInt(),
            DegreeProgram::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS . '_' . $languageCode,
            $rawView->germanLanguageSkillsForInternationalStudents()->name()->asString($languageCode)
        );

        if ($rawView->start()->offsetExists(0)) {
            update_post_meta(
                $rawView->id()->asInt(),
                DegreeProgram::START . '_' . $languageCode,
                $rawView->start()->offsetGet(0)->asString($languageCode),
            );
        }

        if ($rawView->location()->offsetExists(0)) {
            update_post_meta(
                $rawView->id()->asInt(),
                DegreeProgram::LOCATION . '_' . $languageCode,
                $rawView->location()->offsetGet(0)->asString($languageCode)
            );
        }

        $admissionRequirement = AdmissionRequirementsTranslated::fromAdmissionRequirements(
            $rawView->admissionRequirements(),
            $languageCode,
        )
            ->mainLink();

        if (!$admissionRequirement instanceof AdmissionRequirementTranslated) {
            return;
        }

        update_post_meta(
            $rawView->id()->asInt(),
            DegreeProgram::ADMISSION_REQUIREMENTS . '_' . $languageCode,
            $admissionRequirement->name()
        );
    }
}
