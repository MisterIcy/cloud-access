<?php
/**
 * Created by PhpStorm.
 * User: icy
 * Date: 15/10/2018
 * Time: 21:58
 */

namespace mistericy\CloudAccess\Storage;


abstract class StorageFactory implements StorageInterface
{
    abstract public function __construct(AccessorFactory $accessor);
}