<?php
/**
 * Created by PhpStorm.
 * User: icy
 * Date: 15/10/2018
 * Time: 22:07
 */

namespace mistericy\CloudAccess\Storage\NextCloud;

use Exception;
use Throwable;

class NextCloudStorageException extends Exception implements Throwable
{
    public static function RecursiveDeleteOnly()
    {
        return new self("Recursive deletion of folders is mandatory");
    }
    public static function FileNotFound(string $filePath) {
        return new self("I wasn't able to find {$filePath}", 404);
    }
}