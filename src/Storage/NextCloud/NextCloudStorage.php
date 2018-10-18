<?php
/**
 * Created by PhpStorm.
 * User: icy
 * Date: 15/10/2018
 * Time: 21:55
 */

namespace mistericy\CloudAccess\Storage\NextCloud;


use mistericy\CloudAccess\Storage\AccessorFactory;
use mistericy\CloudAccess\Storage\Response;
use mistericy\CloudAccess\Storage\StorageFactory;
use mistericy\CloudAccess\Storage\StorageInterface;

class NextCloudStorage extends StorageFactory
{
    /** @var NextCloudAccessor */
    private $nextCloudAccessor;

    /**
     * NextCloudStorage constructor.
     * @param NextCloudAccessor $accessor
     * @throws NextCloudStorageException
     */
    public function __construct(AccessorFactory $accessor)
    {
        if ($accessor instanceof NextCloudAccessor) {
            $this->nextCloudAccessor = $accessor;
        } else {
            throw NextCloudStorageException::invalidAccessor();
        }

    }

    /**
     * @param string $filePath
     * @param Response|null $response
     * @return bool
     */
    public function fileExists(string $filePath, Response &$response = null): bool
    {
        $response = $this->nextCloudAccessor->doRequest(
            "PROPFIND",
            $filePath
        );

        return !($response->getStatusCode() === 404);
    }

    /**
     * @param string $folderPath
     * @param Response|null $response
     * @return bool
     */
    public function folderExists(string $folderPath, Response &$response = null): bool
    {
        $response = $this->nextCloudAccessor->doRequest(
            "PROPFIND",
            $folderPath
        );

        return !($response->getStatusCode() === 404);
    }

    /**
     * @param string $filePath
     * @param $data
     * @param Response|null $response
     * @return bool
     * @throws NextCloudStorageException
     */
    public function createFile(string $filePath, $data, Response &$response = null): bool
    {
        $checkAndCreatePath = $this->createFolderPath($filePath, $response);
        if (!$checkAndCreatePath) {
            throw NextCloudStorageException::unableToCreateFolder();
        }

        $response = $this->nextCloudAccessor->doRequest(
            "PUT",
            $filePath,
            $data
        );

        return ($response->getStatusCode() === 201);
    }

    public function createFolderPath(string $filePath, Response &$response = null) : bool
    {
        $pathToCreate = explode("/", $filePath);
        $currentPath = "";
        for ($p = 0; $p<count($pathToCreate) - 1; $p++) {
            //Last element of the array is the file
            $currentPath .= "/" . $pathToCreate[$p];
            $exists = $this->folderExists($currentPath, $response);
            if (!$exists) {
                $created = $this->createFolder($currentPath, $response);
                if (!$created) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param string $folderPath
     * @param Response|null $response
     * @return bool
     */
    public function createFolder(string $folderPath, Response &$response = null): bool
    {
        $response = $this->nextCloudAccessor->doRequest(
            "MKCOL",
            $folderPath
        );

        return ($response->getStatusCode() === 201);
    }

    /**
     * @param string $filePath
     * @param Response|null $response
     * @return bool
     */
    public function deleteFile(string $filePath, Response &$response = null): bool
    {
        $response = $this->nextCloudAccessor->doRequest(
            "DELETE",
            $filePath
        );
        return ($response->getStatusCode() === 204);
    }

    /**
     * @param string $folderPath
     * @param bool $recursive
     * @param Response|null $response
     * @return bool
     * @throws NextCloudStorageException
     */
    public function deleteFolder(string $folderPath, bool $recursive = false, Response &$response = null): bool
    {
        if (!$recursive) {
            throw NextCloudStorageException::RecursiveDeleteOnly();
        }
        $response = $this->nextCloudAccessor->doRequest(
            "DELETE",
            $folderPath
        );

        return ($response->getStatusCode() === 204);
    }

    /**
     * @param string $filePath
     * @param Response $response
     * @return string
     * @throws NextCloudStorageException
     */
    public function getFile(string $filePath, Response &$response = null)
    {
        $response = $this->nextCloudAccessor->doRequest(
            "GET",
            $filePath
        );

        if ($response->getStatusCode() === 404) {
            throw NextCloudStorageException::FileNotFound($filePath);
        }
        return $response->getBody();
    }
}