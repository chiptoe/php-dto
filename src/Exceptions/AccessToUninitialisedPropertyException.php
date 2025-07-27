<?php
declare(strict_types=1);

namespace Project\Exceptions;

use Exception;

class AccessToUninitialisedPropertyException extends \Exception
{
    public function __construct() {
        parent::__construct('Access to uninitialised property is forbidden.', 0, null);
    }
}
