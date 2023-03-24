<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Cache;

use Fau\DegreeProgram\Common\Application\Cache\CacheKeyGenerator;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\MultilingualString;

/**
 * @psalm-import-type DegreeProgramViewRawArrayType from DegreeProgramViewRaw
 * @psalm-import-type DegreeProgramViewTranslatedArrayType from DegreeProgramViewTranslated
 */
final class CachedDataTransformer
{
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

        return $data;
    }
}
