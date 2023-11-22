<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Cache;

use Fau\DegreeProgram\Common\Application\Cache\CacheKeyGenerator;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\RelatedDegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Psr\Log\LoggerInterface;

/**
 * @psalm-import-type DegreeProgramViewRawArrayType from DegreeProgramViewRaw
 * @psalm-import-type DegreeProgramViewTranslatedArrayType from DegreeProgramViewTranslated
 * @psalm-import-type RelatedDegreeProgramType from RelatedDegreeProgram
 */
final class CachedDataTransformer
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    /**
     * Ensure we have correct cached data that points to the local DB entries.
     *
     * @template TType of 'raw' | 'translated'
     * @param int $postId Persisted post ID
     * @param DegreeProgramViewRawArrayType|DegreeProgramViewTranslatedArrayType $data
     * @param TType $type
     * @return (TType is 'raw'
     *          ? DegreeProgramViewRawArrayType
     *          : DegreeProgramViewTranslatedArrayType
     * )
     */
    public function transform(int $postId, array $data, string $type): array
    {
        $data[DegreeProgram::ID] = $postId;

        if ($type === CacheKeyGenerator::RAW_TYPE) {
            /** @var DegreeProgramViewRawArrayType $data */
            return $data;
        }

        /** @var DegreeProgramViewTranslatedArrayType $data */
        $permalink = get_the_permalink($postId);
        if (!$permalink) {
            return $data;
        }
        $data[DegreeProgramViewTranslated::LINK] = $permalink;
        $englishTranslation = &$data[DegreeProgramViewTranslated::TRANSLATIONS][MultilingualString::EN];
        $englishTranslation[DegreeProgramViewTranslated::LINK] = str_replace(
            $data[DegreeProgram::SLUG],
            $englishTranslation[DegreeProgram::SLUG],
            $permalink
        );

        $data[DegreeProgram::COMBINATIONS] = $this->updateRelatedPostUrls(
            $data[DegreeProgram::COMBINATIONS]
        );
        $data[DegreeProgram::LIMITED_COMBINATIONS] = $this->updateRelatedPostUrls(
            $data[DegreeProgram::LIMITED_COMBINATIONS]
        );
        $englishTranslation[DegreeProgram::COMBINATIONS] = $this->updateRelatedPostUrls(
            $englishTranslation[DegreeProgram::COMBINATIONS]
        );
        $englishTranslation[DegreeProgram::LIMITED_COMBINATIONS] = $this->updateRelatedPostUrls(
            $englishTranslation[DegreeProgram::LIMITED_COMBINATIONS]
        );

        return $data;
    }

    /**
     * @param array<RelatedDegreeProgramType> $combinations
     * @return array<RelatedDegreeProgramType>
     */
    private function updateRelatedPostUrls(array $combinations): array
    {
        $result = [];
        foreach ($combinations as $combination) {
            $combination[RelatedDegreeProgram::URL] = $this->replaceUrlWithTheCurrent(
                $combination[RelatedDegreeProgram::URL]
            );
            $result[] = $combination;
        }

        return $result;
    }

    /**
     * Replace https://fau.providing.website/studiengang/german-studies-ba/
     * with https://fau.consuming.webiste/studiengang/german-studies-ba/
     *
     * @param string $url
     * @return string
     */
    private function replaceUrlWithTheCurrent(string $url): string
    {
        $parts = explode(DegreeProgramPostType::KEY, $url);
        if (count($parts) !== 2) {
            $this->logger->error(
                sprintf(
                    'Could not replace URL "%s" with the current consuming website.',
                    $url
                )
            );

            return $url;
        }

        $parts[0] = home_url('/');
        return implode(DegreeProgramPostType::KEY, $parts);
    }
}
