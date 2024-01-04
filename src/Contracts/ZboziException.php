<?php

declare(strict_types=1);

namespace Soukicz\Zbozicz\Contracts;

use Throwable;

/**
 * Interface ZboziException
 *
 * This Exception Interface wraps all exceptions thrown by this library.
 *
 * @package Soukicz\Zbozicz\Contracts
 *
 * @since 2.0
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
interface ZboziException extends Throwable
{
}
