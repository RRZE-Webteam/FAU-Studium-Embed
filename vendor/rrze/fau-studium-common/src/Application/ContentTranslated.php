<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Application;

use Fau\DegreeProgram\Common\Domain\Content;
use Fau\DegreeProgram\Common\Domain\ContentItem;

/**
 * @psalm-import-type ContentItemTranslatedType from ContentItemTranslated
 * @psalm-type ContentTranslatedType = array{
 *     about: ContentItemTranslatedType,
 *     structure: ContentItemTranslatedType,
 *     specializations: ContentItemTranslatedType,
 *     qualities_and_skills: ContentItemTranslatedType,
 *     why_should_study: ContentItemTranslatedType,
 *     career_prospects: ContentItemTranslatedType,
 *     special_features: ContentItemTranslatedType,
 *     testimonials: ContentItemTranslatedType,
 * }
 */
final class ContentTranslated
{
    private function __construct(
        private ContentItemTranslated $about,
        private ContentItemTranslated $structure,
        private ContentItemTranslated $specializations,
        private ContentItemTranslated $qualitiesAndSkills,
        private ContentItemTranslated $whyShouldStudy,
        private ContentItemTranslated $careerProspects,
        private ContentItemTranslated $specialFeatures,
        private ContentItemTranslated $testimonials,
    ) {
    }

    public static function new(
        ContentItemTranslated $about,
        ContentItemTranslated $structure,
        ContentItemTranslated $specializations,
        ContentItemTranslated $qualitiesAndSkills,
        ContentItemTranslated $whyShouldStudy,
        ContentItemTranslated $careerProspects,
        ContentItemTranslated $specialFeatures,
        ContentItemTranslated $testimonials
    ): self {

        return new self(
            $about,
            $structure,
            $specializations,
            $qualitiesAndSkills,
            $whyShouldStudy,
            $careerProspects,
            $specialFeatures,
            $testimonials
        );
    }

    public static function fromContent(Content $content, string $languageCode): self
    {
        return new self(
            ContentItemTranslated::fromContentItem($content->about(), $languageCode),
            ContentItemTranslated::fromContentItem($content->structure(), $languageCode),
            ContentItemTranslated::fromContentItem($content->specializations(), $languageCode),
            ContentItemTranslated::fromContentItem($content->qualitiesAndSkills(), $languageCode),
            ContentItemTranslated::fromContentItem($content->whyShouldStudy(), $languageCode),
            ContentItemTranslated::fromContentItem($content->careerProspects(), $languageCode),
            ContentItemTranslated::fromContentItem($content->specialFeatures(), $languageCode),
            ContentItemTranslated::fromContentItem($content->testimonials(), $languageCode),
        );
    }

    /**
     * @psalm-param ContentTranslatedType $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            ContentItemTranslated::fromArray($data[Content::ABOUT]),
            ContentItemTranslated::fromArray($data[Content::STRUCTURE]),
            ContentItemTranslated::fromArray($data[Content::SPECIALIZATIONS]),
            ContentItemTranslated::fromArray($data[Content::QUALITIES_AND_SKILLS]),
            ContentItemTranslated::fromArray($data[Content::WHY_SHOULD_STUDY]),
            ContentItemTranslated::fromArray($data[Content::CAREER_PROSPECTS]),
            ContentItemTranslated::fromArray($data[Content::SPECIAL_FEATURES]),
            ContentItemTranslated::fromArray($data[Content::TESTIMONIALS]),
        );
    }

    /**
     * @return ContentTranslatedType
     */
    public function asArray(): array
    {
        return [
            Content::ABOUT => $this->about->asArray(),
            Content::STRUCTURE => $this->structure->asArray(),
            Content::SPECIALIZATIONS => $this->specializations->asArray(),
            Content::QUALITIES_AND_SKILLS => $this->qualitiesAndSkills->asArray(),
            Content::WHY_SHOULD_STUDY => $this->whyShouldStudy->asArray(),
            Content::CAREER_PROSPECTS => $this->careerProspects->asArray(),
            Content::SPECIAL_FEATURES => $this->specialFeatures->asArray(),
            Content::TESTIMONIALS => $this->testimonials->asArray(),
        ];
    }

    /**
     * @psalm-param callable(string): string $callback
     */
    public function mapDescriptions(callable $callback): self
    {
        $content = $this->asArray();
        $contentItems = [];
        foreach ($content as $item) {
            $contentItems[] = ContentItemTranslated::new(
                $item[ContentItem::TITLE],
                $callback(
                    $item[ContentItem::DESCRIPTION]
                )
            );
        }
        return new self(...$contentItems);
    }

    public function about(): ContentItemTranslated
    {
        return $this->about;
    }

    public function structure(): ContentItemTranslated
    {
        return $this->structure;
    }

    public function specializations(): ContentItemTranslated
    {
        return $this->specializations;
    }

    public function qualitiesAndSkills(): ContentItemTranslated
    {
        return $this->qualitiesAndSkills;
    }

    public function whyShouldStudy(): ContentItemTranslated
    {
        return $this->whyShouldStudy;
    }

    public function careerProspects(): ContentItemTranslated
    {
        return $this->careerProspects;
    }

    public function specialFeatures(): ContentItemTranslated
    {
        return $this->specialFeatures;
    }

    public function testimonials(): ContentItemTranslated
    {
        return $this->testimonials;
    }
}
