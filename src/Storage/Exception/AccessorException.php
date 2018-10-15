<?php
/**
 * Created by PhpStorm.
 * User: icy
 * Date: 15/10/2018
 * Time: 21:08
 */

namespace mistericy\CloudAccess\Storage\Exception;

use Exception;
use Throwable;

class AccessorException extends Exception implements Throwable
{
    public static function invalidSettingsForAccessor(string $accessor)
    {
        return new self("The settings supplied for accessor ${accessor} are invalid");
    }

}