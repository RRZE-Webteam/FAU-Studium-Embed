<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Output\Infrastructure\Cache;

use Psr\SimpleCache\CacheException;
use RuntimeException;
use WP_Error;

final class CouldNotInsertPost extends RuntimeException implements CacheException
{
    public static function fromWpError(WP_Error $error): self
    {
        return new self(
            sprintf(
                'Could not insert post. Error message: %s.',
                $error->get_error_message()
            )
        );
    }
}
