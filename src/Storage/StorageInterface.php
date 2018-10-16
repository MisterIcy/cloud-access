<?php
/**
 * Created by PhpStorm.
 * User: icy
 * Date: 15/10/2018
 * Time: 21:55
 */

namespace mistericy\CloudAccess\Storage;


interface StorageInterface
{
    public function fileExists(string $filePath, Response &$response = null) : bool;
    public function folderExists(string $folderPath, Response &$response = null) : bool;

    public function createFile(string $filePath, $data, Response &$response = null) : bool;
    public function createFolder(string $folderPath, Response &$response = null) : bool ;

    public function deleteFile(string $filePath, Response &$response = null) : bool;
    public function deleteFolder(string $folderPath, bool $recursive = false, Response &$response = null) : bool;

    public function getFile(string $filePath, Response &$response);
}