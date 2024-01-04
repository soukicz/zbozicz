<?php

declare(strict_types=1);

namespace Soukicz\Zbozicz\Exceptions;

use LogicException;
use Soukicz\Zbozicz\Contracts\ZboziException;

/**
 * Class InvalidOrderIDException
 *
 * @package Soukicz\Zbozicz\Exceptions
 *
 * @since 2.0
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
final class InvalidOrderIDException extends LogicException implements ZboziException
{
}
