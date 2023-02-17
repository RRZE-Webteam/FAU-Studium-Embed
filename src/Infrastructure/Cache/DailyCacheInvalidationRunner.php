<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Cache;

use Fau\DegreeProgram\Common\Application\Cache\CacheInvalidator;

final class DailyCacheInvalidationRunner
{
    private const DISABLE_DAILY_CACHE_INVALIDATION = 'FAU_DISABLE_DAILY_CACHE_INVALIDATION';

    public const DAILY_CACHE_INVALIDATION_HOOK = 'fau_daily_cache_invalidation';

    public function __construct(private CacheInvalidator $cacheInvalidator)
    {
    }

    public function scheduleDailyCacheInvalidation(): void
    {
        if ($this->isDisabled()) {
            $this->unscheduleDailyCacheInvalidation();
        }

        if (wp_next_scheduled(self::DAILY_CACHE_INVALIDATION_HOOK)) {
            return;
        }

        wp_schedule_event(time(), 'daily', self::DAILY_CACHE_INVALIDATION_HOOK);
    }

    public function unscheduleDailyCacheInvalidation(): void
    {
        wp_clear_scheduled_hook(self::DAILY_CACHE_INVALIDATION_HOOK);
    }

    public function runDailyCacheInvalidation(): void
    {
        if ($this->isDisabled()) {
            return;
        }

        $this->cacheInvalidator->invalidateFully();
    }

    private function isDisabled(): bool
    {
        return (
                defined(self::DISABLE_DAILY_CACHE_INVALIDATION)
                && constant(self::DISABLE_DAILY_CACHE_INVALIDATION)
            )
            || getenv(self::DISABLE_DAILY_CACHE_INVALIDATION);
    }
}
