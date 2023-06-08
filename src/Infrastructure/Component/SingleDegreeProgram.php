<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Component;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Domain\Content;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Domain\MultilingualString;
use Fau\DegreeProgram\Common\Infrastructure\TemplateRenderer\Renderer;
use Fau\DegreeProgram\Output\Application\DegreeProgramViewPropertiesFilter;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\LocaleHelper;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\ReferrerUrlHelper;
use Psr\Log\LoggerInterface;

/**
 * @psalm-import-type LanguageCodes from MultilingualString
 * @psalm-type SingleDegreeProgramAttributes = array{
 *     id: int,
 *     lang: LanguageCodes,
 *     include: array<string>,
 *     exclude: array<string>,
 *     format: 'full' | 'short',
 *     className: string,
 * }
 */
final class SingleDegreeProgram implements RenderableComponent
{
    private const DEFAULT_ATTRIBUTES = [
        'id' => 0,
        'lang' => MultilingualString::DE,
        'include' => [],
        'exclude' => [],
        'format' => 'full',
        'className' => '',
    ];

    public function __construct(
        private Renderer $renderer,
        private DegreeProgramViewRepository $degreeProgramViewRepository,
        private LoggerInterface $logger,
        private ReferrerUrlHelper $referrerUrlHelper,
        private DegreeProgramViewPropertiesFilter $degreeProgramViewPropertiesFilter,
        private CurrentRequest $currentRequest,
    ) {
    }

    public function render(array $attributes = self::DEFAULT_ATTRIBUTES): string
    {
        $localeHelper = LocaleHelper::new();
        $attributes['lang'] = $attributes['lang'] ?? $this->currentRequest->languageCode();

        /** @var SingleDegreeProgramAttributes $attributes */
        $attributes = wp_parse_args($attributes, self::DEFAULT_ATTRIBUTES);
        if (!$attributes['id']) {
            $this->logger->warning(
                'It is not possible to render single degree program without ID.',
                [
                    'post_id' => get_the_ID(),
                ]
            );

            return '';
        }

        $view = $this->degreeProgramViewRepository->findTranslated(
            DegreeProgramId::fromInt($attributes['id']),
            $attributes['lang']
        );

        $view = apply_filters(
            'fau.degree-program-output.single-view',
            $view,
            $attributes['id'],
            $attributes['lang']
        );

        if (!$view instanceof DegreeProgramViewTranslated) {
            return '';
        }

        $view = $this->filterViewProperties(
            $view,
            $attributes['include'],
            $attributes['exclude'],
        );

        $localeHelper = $localeHelper->withLocale(
            $localeHelper->localeFromLanguageCode($attributes['lang'])
        );

        add_filter('locale', [$localeHelper, 'filterLocale']);
        $html = $this->renderer->render(
            'single-degree-program-' . $attributes['format'],
            [
                'view' => $view,
                'referrerUrlHelper' => $this->referrerUrlHelper,
                'className' => $attributes['className'],
            ]
        );
        remove_filter('locale', [$localeHelper, 'filterLocale']);

        return $html;
    }

    /**
     * @param array<string> $include
     * @param array<string> $exclude
     */
    private function filterViewProperties(
        DegreeProgramViewTranslated $view,
        array $include,
        array $exclude
    ): DegreeProgramViewTranslated {

        if ($include) {
            return $this->degreeProgramViewPropertiesFilter->include($view, $include);
        }

        if ($exclude) {
            return $this->degreeProgramViewPropertiesFilter->exclude($view, $exclude);
        }

        return $view;
    }

    // phpcs:ignore Inpsyde.CodeQuality.FunctionLength.TooLong
    public static function supportedProperties(): array
    {
        return [
            DegreeProgram::TEASER_IMAGE => __('Teaser image', 'fau-degree-program-output'),
            DegreeProgram::TITLE => __('Title', 'fau-degree-program-output'),
            DegreeProgram::SUBTITLE => __('Subtitle', 'fau-degree-program-output'),
            DegreeProgram::STANDARD_DURATION => __(
                'Standard duration of study',
                'fau-degree-program-output'
            ),
            DegreeProgram::START => __('Start of degree program', 'fau-degree-program-output'),
            DegreeProgram::NUMBER_OF_STUDENTS => __(
                'Number of students',
                'fau-degree-program-output'
            ),
            DegreeProgram::TEACHING_LANGUAGE => __(
                'Teaching language',
                'fau-degree-program-output'
            ),
            DegreeProgram::ATTRIBUTES => __('Attributes', 'fau-degree-program-output'),
            DegreeProgram::DEGREE => __('Degree', 'fau-degree-program-output'),
            DegreeProgram::FACULTY => __('Faculty', 'fau-degree-program-output'),
            DegreeProgram::LOCATION => __('Study location', 'fau-degree-program-output'),
            DegreeProgram::SUBJECT_GROUPS => __('Subject groups', 'fau-degree-program-output'),
            DegreeProgram::VIDEOS => __('Videos', 'fau-degree-program-output'),
            sprintf('%s.%s', DegreeProgram::CONTENT, Content::ABOUT) => __(
                'What is the degree program about?',
                'fau-degree-program-output'
            ),
            sprintf('%s.%s', DegreeProgram::CONTENT, Content::STRUCTURE) => __(
                'Design and structure',
                'fau-degree-program-output'
            ),
            sprintf('%s.%s', DegreeProgram::CONTENT, Content::SPECIALIZATIONS) => __(
                'Fields of study and specializations',
                'fau-degree-program-output'
            ),
            sprintf('%s.%s', DegreeProgram::CONTENT, Content::QUALITIES_AND_SKILLS) => __(
                'Which qualities and skills do I need?',
                'fau-degree-program-output'
            ),
            sprintf('%s.%s', DegreeProgram::CONTENT, Content::WHY_SHOULD_STUDY) => __(
                'Why should I study at FAU?',
                'fau-degree-program-output'
            ),
            sprintf('%s.%s', DegreeProgram::CONTENT, Content::CAREER_PROSPECTS) => __(
                'Which career prospects are open to me?',
                'fau-degree-program-output'
            ),
            DegreeProgramViewTranslated::ADMISSION_REQUIREMENT_LINK => __(
                'Admission requirement link',
                'fau-degree-program-output'
            ),
            DegreeProgram::DETAILS_AND_NOTES => __(
                'Details and notes',
                'fau-degree-program-output'
            ),
            DegreeProgram::START_OF_SEMESTER => __(
                'Start of semester',
                'fau-degree-program-output'
            ),
            DegreeProgram::SEMESTER_DATES => __('Semester dates', 'fau-degree-program-output'),
            DegreeProgram::EXAMINATIONS_OFFICE => __(
                'Examinations Office',
                'fau-degree-program-output'
            ),
            DegreeProgram::EXAMINATION_REGULATIONS => __(
                'Degree program and examination regulations',
                'fau-degree-program-output'
            ),
            DegreeProgram::MODULE_HANDBOOK => __('Module handbook', 'fau-degree-program-output'),
            DegreeProgram::URL => __('Degree program URL', 'fau-degree-program-output'),
            DegreeProgram::DEPARTMENT => __(
                'Department/Institute',
                'fau-degree-program-output'
            ),
            DegreeProgram::STUDENT_ADVICE => __(
                'Student Advice and Career Service',
                'fau-degree-program-output'
            ),
            DegreeProgram::SUBJECT_SPECIFIC_ADVICE => __(
                'Subject-specific advice',
                'fau-degree-program-output'
            ),
            DegreeProgram::SERVICE_CENTERS => __(
                'Counseling and Service Centers at FAU',
                'fau-degree-program-output'
            ),
            DegreeProgram::INFO_BROCHURE => __(
                'Info brochure degree program',
                'fau-degree-program-output'
            ),
            DegreeProgram::SEMESTER_FEE => __('Semester fee', 'fau-degree-program-output'),
            DegreeProgram::ABROAD_OPPORTUNITIES => __(
                'Opportunities for spending time abroad',
                'fau-degree-program-output'
            ),
            DegreeProgram::KEYWORDS => __('Keywords', 'fau-degree-program-output'),
            DegreeProgram::AREA_OF_STUDY => __('Area of study', 'fau-degree-program-output'),
            DegreeProgram::COMBINATIONS => __('Combination options', 'fau-degree-program-output'),
            DegreeProgram::LIMITED_COMBINATIONS => __(
                'Limited combination options',
                'fau-degree-program-output'
            ),
            DegreeProgram::NOTES_FOR_INTERNATIONAL_APPLICANTS => __(
                'Notes for international applicants',
                'fau-degree-program-output'
            ),
            DegreeProgram::STUDENT_INITIATIVES => __(
                'Students\' Union/Student Initiatives',
                'fau-degree-program-output'
            ),
            DegreeProgram::APPLY_NOW_LINK => __('"Apply now" link', 'fau-degree-program-output'),
            DegreeProgram::ENTRY_TEXT => __(
                'Entry text (promotional)',
                'fau-degree-program-output'
            ),
            DegreeProgram::CONTENT_RELATED_MASTER_REQUIREMENTS => __(
                'Content-related admission requirements for Master\'s degree',
                'fau-degree-program-output'
            ),
            DegreeProgram::APPLICATION_DEADLINE_WINTER_SEMESTER => __(
                'Application deadline winter semester',
                'fau-degree-program-output'
            ),
            DegreeProgram::APPLICATION_DEADLINE_SUMMER_SEMESTER => __(
                'Application deadline summer semester',
                'fau-degree-program-output'
            ),
            DegreeProgram::LANGUAGE_SKILLS => __('Language skills', 'fau-degree-program-output'),
            DegreeProgram::LANGUAGE_SKILLS_HUMANITIES_FACULTY => __(
                'Language skills for Faculty of Humanities, Social Sciences, and Theology only',
                'fau-degree-program-output'
            ),
            DegreeProgram::GERMAN_LANGUAGE_SKILLS_FOR_INTERNATIONAL_STUDENTS => __(
                'Language certificates/German language skills for international applicants',
                'fau-degree-program-output'
            ),
            DegreeProgram::DEGREE_PROGRAM_FEES => __(
                'Degree Program Fees',
                'fau-degree-program-output'
            ),
        ];
    }
}
