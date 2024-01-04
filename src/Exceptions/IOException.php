<?php

declare(strict_types=1);

namespace Soukicz\Zbozicz\Exceptions;

use RuntimeException;
use Soukicz\Zbozicz\Contracts\ZboziException;

/**
 * Class IOException
 *
 * @package Soukicz\Zbozicz\Exceptions
 *
 * @since 2.0
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
final class IOException extends RuntimeException implements ZboziException
{
}
