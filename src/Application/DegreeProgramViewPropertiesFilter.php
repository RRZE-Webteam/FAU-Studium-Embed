<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Application;

use Fau\DegreeProgram\Common\Application\DegreeProgramViewTranslated;
use RuntimeException;

/**
 * @psalm-import-type DegreeProgramViewTranslatedArrayType from DegreeProgramViewTranslated
 *
 * Translations are not covered since we don't need it for the single view.
 */
final class DegreeProgramViewPropertiesFilter
{
    public function __construct(
        private ArrayPropertiesAccessor $arrayPropertiesAccessor
    ) {
    }

    /**
     * @psalm-param array<string> $include
     */
    public function include(
        DegreeProgramViewTranslated $view,
        array $include
    ): DegreeProgramViewTranslated {

        return $this->copy(
            $view,
            DegreeProgramViewTranslated::empty(
                $view->id(),
                $view->lang(),
            ),
            $include
        );
    }

    /**
     * @psalm-param array<string> $exclude
     */
    public function exclude(
        DegreeProgramViewTranslated $view,
        array $exclude
    ): DegreeProgramViewTranslated {

        return $this->copy(
            DegreeProgramViewTranslated::empty(
                $view->id(),
                $view->lang(),
            ),
            $view,
            $exclude
        );
    }

    /**
     * @psalm-param array<string> $properties
     */
    private function copy(
        DegreeProgramViewTranslated $from,
        DegreeProgramViewTranslated $to,
        array $properties
    ): DegreeProgramViewTranslated {

        $fromData = $from->asArray();
        $toData = $to->asArray();
        foreach ($properties as $path) {
            try {
                $value = $this->arrayPropertiesAccessor->get($fromData, $path);
                $this->arrayPropertiesAccessor->set($toData, $path, $value);
            } catch (RuntimeException) {
            }
        }

        /** @psalm-var DegreeProgramViewTranslatedArrayType $toData */
        return DegreeProgramViewTranslated::fromArray($toData);
    }
}
