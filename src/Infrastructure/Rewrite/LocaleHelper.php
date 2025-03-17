<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Rewrite;

use Fau\DegreeProgram\Common\Domain\MultilingualString;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 */
final class LocaleHelper
{
    private function __construct(private string $locale = '')
    {
    }

    public static function new(): self
    {
        return new self();
    }

    public function withLocale(string $locale): self
    {
        return new self($locale);
    }

    /**
     * @psalm-param LanguageCodes $languageCode
     */
    public function localeFromLanguageCode(string $languageCode): string
    {
        if ($languageCode === MultilingualString::EN) {
            return 'en_US';
        }

        if (in_array(get_locale(), ['de_DE', 'de_DE_formal'], true)) {
            return get_locale();
        }

        return 'de_DE';
    }
}
