<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Cache;

use DateInterval;
use Fau\DegreeProgram\Common\Application\Cache\CacheKeyGenerator;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\Cache\PostMetaDegreeProgramCache;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Common\Infrastructure\Repository\BilingualRepository;
use LogicException;
use Psr\SimpleCache\CacheInterface;
use Webmozart\Assert\Assert;
use WP_Error;
use WP_Post;

/**
 * @psalm-import-type DegreeProgramViewRawArrayType from DegreeProgramViewRaw
 * @psalm-import-type DegreeProgramViewTranslatedArrayType from DegreeProgramViewTranslated
 */
final class PostDegreeProgramCache implements CacheInterface
{
    public const ORIGINAL_ID_KEY = 'fau_original_id';
    public const ORIGINAL_LINK_KEY = 'fau_original_link';

    public function __construct(
        private PostMetaDegreeProgramCache $postMetaCache,
        private CacheKeyGenerator $cacheKeyGenerator,
        private CachedDataTransformer $cachedDataTransformer,
    ) {
    }

    public function get(string $key, mixed $default = null): ?array
    {
        [,$postId] = $this->cacheKeyGenerator->parseForDegreeProgram($key);

        if (!$this->isValidPostId($postId)) {
            return null;
        }

        return $this->postMetaCache->get($key);
    }

    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        Assert::isArray($value);
        /** @var DegreeProgramViewRawArrayType|DegreeProgramViewTranslatedArrayType $value*/

        [$type, $postId] = $this->cacheKeyGenerator->parseForDegreeProgram($key);
        if ($this->isValidPostId($postId)) {
            return $this->postMetaCache->set($key, $value);
        }

        $postId = $this->maybePersistPost($postId, $value, $type);
        if ($postId instanceof WP_Error) {
            throw CouldNotInsertPost::fromWpError($postId);
        }

        $newKey = $this->cacheKeyGenerator->generateForDegreeProgram(DegreeProgramId::fromInt($postId), $type);
        $value = $this->cachedDataTransformer->transform($postId, $value, $type);

        return $this->postMetaCache->set($newKey, $value);
    }

    /**
     * @param DegreeProgramViewRawArrayType|DegreeProgramViewTranslatedArrayType $data
     * @param 'raw'|'translated' $type
     */
    private function maybePersistPost(int $postId, array $data, string $type): int|WP_Error
    {
        $post = get_post($postId);
        $postData = $this->generatePostData($data, $type);

        if (
            $post instanceof WP_Post
            && $post->post_type === DegreeProgramPostType::KEY
            && $post->post_status === 'trash'
        ) {
            // The post ID is from our database.
            wp_untrash_post($postId);
            $postData['ID'] = $postId;
            return wp_update_post($postData, true);
        }

        /** @var array<int> $ids */
        $ids = get_posts([
            'numberposts' => 1,
            'post_type' => DegreeProgramPostType::KEY,
            'post_status' => ['publish', 'trash'],
            'meta_query' => [
                [
                    'key' => self::ORIGINAL_ID_KEY,
                    'value' => $postId,
                    'type' => 'UNSIGNED',
                ],
            ],
            'fields' => 'ids',
        ]);

        if (count($ids) === 0) {
            // The post ID is from the remote database.
            $postData['meta_input'][self::ORIGINAL_ID_KEY] = $postId;
            return wp_insert_post($postData, true);
        }

        // The post ID is from the remote database, but we have cached the post already.
        $postId = $ids[0];
        /** @var WP_Post $post */
        $post = get_post($postId);
        if ($post->post_status === 'trash') {
            wp_untrash_post($postId);
        }

        $postData['ID'] = $postId;
        return wp_update_post($postData, true);
    }

    /**
     * @template TType of 'raw' | 'translated'
     * @param DegreeProgramViewRawArrayType|DegreeProgramViewTranslatedArrayType $data
     * @param TType $type
     * @psalm-return array{
     *     post_title: string,
     *     post_type: string,
     *     post_status: string,
     *     post_name: string,
     *     meta_input: array<string, string>
     * }
     *
     * phpcs:disable Inpsyde.CodeQuality.LineLength.TooLong
     */
    private function generatePostData(array $data, string $type): array
    {
        if ($type === CacheKeyGenerator::RAW_TYPE) {
            /** @var DegreeProgramViewRawArrayType $data */

            $slug = $data[DegreeProgram::SLUG];
            return [
                'post_title' => $data[DegreeProgram::TITLE][MultilingualString::DE],
                'post_type' => DegreeProgramPostType::KEY,
                'post_status' => 'publish',
                'post_name' => $slug[MultilingualString::DE],
                'meta_input' => [
                    BilingualRepository::addEnglishSuffix('post_name') => $slug[MultilingualString::EN],
                ],
            ];
        }

        if ($type === CacheKeyGenerator::TRANSLATED_TYPE) {
            /** @var DegreeProgramViewTranslatedArrayType $data */
            $englishTranslation = $data[DegreeProgramViewTranslated::TRANSLATIONS][MultilingualString::EN];
            return [
                'post_title' => $data[DegreeProgram::TITLE],
                'post_type' => DegreeProgramPostType::KEY,
                'post_status' => 'publish',
                'post_name' => $data[DegreeProgram::SLUG],
                'meta_input' => [
                    BilingualRepository::addEnglishSuffix('post_name') => $englishTranslation[DegreeProgram::SLUG],
                    self::ORIGINAL_LINK_KEY => $data[DegreeProgramViewTranslated::LINK],
                    BilingualRepository::addEnglishSuffix(self::ORIGINAL_LINK_KEY) => $englishTranslation[DegreeProgramViewTranslated::LINK],
                ],
            ];
        }

        throw new LogicException('Unsupported cache type.');
    }

    public function delete(string $key): bool
    {
        [, $postId] = $this->cacheKeyGenerator->parseForDegreeProgram($key);
        if (!$this->isValidPostId($postId)) {
            return true;
        }

        $result = [];
        $result[] = (bool) wp_trash_post($postId);
        $result[] = $this->postMetaCache->delete($key);

        return !in_array(false, $result, true);
    }

    public function clear(): bool
    {
        /** @var array<int> $ids */
        $ids = get_posts([
            'numberposts' => -1,
            'post_type' => DegreeProgramPostType::KEY,
            'post_status' => ['publish'],
            'fields' => 'ids',
        ]);

        $result = [];
        foreach ($ids as $id) {
            $result[] = (bool) wp_trash_post($id);
        }

        return !in_array(false, $result, true);
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key);
        }

        return $result;
    }

    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        $result = [];
        foreach ($values as $key => $value) {
            $result[] = $this->set((string) $key, $value);
        }

        return !in_array(false, $result, true);
    }

    public function deleteMultiple(iterable $keys): bool
    {
        $result = [];
        foreach ($keys as $key) {
            $result[] = $this->delete($key);
        }

        return !in_array(false, $result, true);
    }

    public function has(string $key): bool
    {
        [, $postId] = $this->cacheKeyGenerator->parseForDegreeProgram($key);

        return $this->isValidPostId($postId) && $this->postMetaCache->has($key);
    }

    private function isValidPostId(int $postId): bool
    {
        if ($postId <= 0) {
            return false;
        }

        $post = get_post($postId);
        if (!$post instanceof WP_Post) {
            return false;
        }

        if ($post->post_type !== DegreeProgramPostType::KEY) {
            return false;
        }

        return $post->post_status === 'publish';
    }
}
