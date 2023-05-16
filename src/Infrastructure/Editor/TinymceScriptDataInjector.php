<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Editor;

use Fau\DegreeProgram\Common\Application\Repository\CollectionCriteria;
use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramCollectionRepository;
use Fau\DegreeProgram\Common\Application\Repository\PaginationAwareCollection;
use Fau\DegreeProgram\Common\Domain\DegreeProgram;
use Fau\DegreeProgram\Output\Infrastructure\Component\SingleDegreeProgram;
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
        $degreeProgramFields = json_encode(SingleDegreeProgram::supportedProperties());
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

    private function formFields(): array
    {
        return [
            'title' => __('FAU Degree Program Shortcode Generator', 'fau-degree-program-output'),
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
