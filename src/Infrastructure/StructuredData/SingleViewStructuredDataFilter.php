<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\StructuredData;

use Fau\DegreeProgram\Common\Application\Repository\DegreeProgramViewRepository;
use Fau\DegreeProgram\Common\Domain\DegreeProgramId;
use Fau\DegreeProgram\Common\Infrastructure\Content\PostType\DegreeProgramPostType;
use Fau\DegreeProgram\Output\Application\StructuredData\Course;
use Fau\DegreeProgram\Output\Infrastructure\Rewrite\CurrentRequest;

final class SingleViewStructuredDataFilter
{
    public function __construct(
        private DegreeProgramViewRepository $degreeProgramViewRepository,
        private CurrentRequest $currentRequest,
        private ScriptBuilder $scriptBuilder,
    ) {
    }

    /**
     * @wp-hook the_seo_framework_ldjson_scripts
     */
    public function outputStructuredData(string $output, int $objectId): string
    {
        if (!is_single($objectId)) {
            return $output;
        }

        if (get_post_type($objectId) !== DegreeProgramPostType::KEY) {
            return $output;
        }

        $view = $this->degreeProgramViewRepository->findTranslated(
            DegreeProgramId::fromInt($objectId),
            $this->currentRequest->languageCode()
        );

        if (!$view) {
            return $output;
        }

        // Replace misleading Breadcrumbs data from SEO framework
        return $this->scriptBuilder->build(
            Course::fromTranslatedView($view)
        );
    }
}
