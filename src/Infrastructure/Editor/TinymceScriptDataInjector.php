<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Editor;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewRaw;
use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Domain\Content;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use WP_Screen;

class TinymceScriptDataInjector
{
    public function __construct(
        private DegreeProgramCollectionRepository $degreeProgramViewRepository,
    ) {
    }

    /**
     * @wp-hook wp_tiny_mce_init
     * @return void
     */
    public function inject(): void
    {
        if (! $this->shouldInject()) {
            return;
        }

        $degreePrograms = $this->degreeProgramViewRepository->findRawCollection(
            CollectionCriteria::new()->withoutPagination()->withOrderby(DegreeProgram::TITLE, 'asc')
        );

        $degreeProgramData = [];

        if ($degreePrograms instanceof PaginationAwareCollection) {
            foreach ($degreePrograms as $degreeProgram) {
                $degreeProgramData[] = [
                    'id' => $degreeProgram->id(),
                    'title' => $degreeProgram->title(),
                ];
            }
        }

        $degreeProgramData = json_encode($degreeProgramData);
        $degreeProgramFields = json_encode($this->degreeProgramFields());
        $formFields = json_encode($this->formFields());

        // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
        printf(
            '<script>%s</script>',
            <<<JS
            window.fauDegreeProgramData = {
                degreePrograms: $degreeProgramData,
                i18n: {
                    degreeProgramFields: $degreeProgramFields,
                    formFields: $formFields,
                }
            };
            JS
        );
    }

    private function shouldInject(): bool
    {
        if (! is_admin()) {
            return false;
        }

        $screen = get_current_screen();
        if (! $screen instanceof WP_Screen) {
            return false;
        }

        return $screen->parent_base === 'edit';
    }

    // phpcs:ignore Inpsyde.CodeQuality.FunctionLength.TooLong
    private function degreeProgramFields(): array
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
                'Department/Institute (URL)',
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

    private function formFields(): array
    {
        return [
            'title' => __('FAU Degree Program Shortcode Builder', 'fau-degree-program-output'),
            'degreeProgram' => __('Degree program', 'fau-degree-program-output'),
            'language' => __('Language', 'fau-degree-program-output'),
            'format' => __('Format', 'fau-degree-program-output'),
            'include' => __('Include', 'fau-degree-program-output'),
            'exclude' => __('Exclude', 'fau-degree-program-output'),
            'includeExcludeIgnoredNotice' => __(
                'The following attributes are ignored if "format" is "short".',
                'fau-degree-program-output'
            ),
            'excludeIgnoredNotice' => __(
                'Ignored if "include" is specified.',
                'fau-degree-program-output'
            ),
        ];
    }
}
