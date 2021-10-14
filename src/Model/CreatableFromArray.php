<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace FAPI\Sylius\Model;

interface CreatableFromArray
{
    /**
     * Create an API response object from the HTTP response from the API server.
     *
     * @return self
     */
    public static function createFromArray(array $data);
}
