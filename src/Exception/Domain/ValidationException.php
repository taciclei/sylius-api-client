<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace FAPI\Sylius\Exception\Domain;

use FAPI\Sylius\Exception\DomainException;

final class ValidationException extends \Exception implements DomainException
{
}
