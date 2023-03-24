<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Application;

use Fau\DegreeProgram\Common\Domain\DegreeProgramId;

/**
 * Returns the view model with providing (remote) website data.
 */
interface OriginalDegreeProgramViewRepository
{
    /**
     * @return OriginalDegreeProgramView|null
     *         Null if data doesn't exist, or we are on the providing website.
     */
    public function find(DegreeProgramId $degreeProgramId): ?OriginalDegreeProgramView;
}
