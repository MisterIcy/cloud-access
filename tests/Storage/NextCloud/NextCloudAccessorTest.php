<?php
/**
 * Created by PhpStorm.
 * User: akoutroulis
 * Date: 16/10/2018
 * Time: 2:31 μμ
 */

namespace mistericy\CloudAccess\Tests\Storage\NextCloud;


use mistericy\CloudAccess\Storage\AccessorFactory;
use mistericy\CloudAccess\Storage\AccessorSettings;
use mistericy\CloudAccess\Storage\Exception\AccessorException;
use mistericy\CloudAccess\Storage\NextCloud\NextCloudStorageException;
use mistericy\CloudAccess\Storage\Response;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class NextCloudAccessorTest extends TestCase
{
    const NEXTCLOUD_DEMO_INSTANCE_URI = "https://demo14.nextcloud.bayton.org/";
    const NEXTCLOUD_DEMO_INSTANCE_USER = 'admin';
    const NEXTCLOUD_DEMO_INSTANCE_PASS = 'admin';

    public function testCreateFileInCloud()
    {
        $nextCloudAccessor = AccessorFactory::createNextCloudAccessor($this->createNextCloudSettings());

        $response = Response::MakeResponse();

        $filename = "UnitTest" . $this->generateRandomString(). ".md";
        $data = $this->generateRandomString();
        $create = $nextCloudAccessor->getStorage()->createFile($filename,$data, $response);

        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals(true, $create);

    }
    public function testCreateFolderInCloud() {
        $nextCloudAccessor = AccessorFactory::createNextCloudAccessor($this->createNextCloudSettings());

        $response = Response::MakeResponse();
        $folderName = "UnitTest" . $this->generateRandomString();

        $create = $nextCloudAccessor->getStorage()->createFolder($folderName, $response);
        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals(true, $create);
    }

    public function testFolderExists() {
        $nextCloudAccessor = AccessorFactory::createNextCloudAccessor($this->createNextCloudSettings());

        $response = Response::MakeResponse();
        $folderName = "UnitTest" . $this->generateRandomString();

        $create = $nextCloudAccessor->getStorage()->createFolder($folderName, $response);
        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals(true, $create);

        $exists = $nextCloudAccessor->getStorage()->folderExists($folderName, $response);
        self::assertEquals(207, $response->getStatusCode());
        self::assertEquals(true, $exists);

        $delete = $nextCloudAccessor->getStorage()->deleteFolder($folderName, true, $response);
        self::assertEquals(204, $response->getStatusCode());
        self::assertEquals(true, $delete);
    }

    public function testDeleteFolderNonRecursively() {
        self::expectException(NextCloudStorageException::class);
        $nextCloudAccessor = AccessorFactory::createNextCloudAccessor($this->createNextCloudSettings());

        $delete = $nextCloudAccessor->getStorage()->deleteFolder("TestFolder", false);

    }
    public function testInvalidAccessorSettings() {
        self::expectException(AccessorException::class);
        $accessorSettings = new AccessorSettings();
        $nextCloudAccessor = AccessorFactory::createNextCloudAccessor($accessorSettings);
    }

    public function testCheckIfFileExists() {
        $nextCloudAccessor = AccessorFactory::createNextCloudAccessor($this->createNextCloudSettings());

        $response = Response::MakeResponse();

        $filename = "UnitTest" . $this->generateRandomString(). ".md";
        $data = $this->generateRandomString();
        $create = $nextCloudAccessor->getStorage()->createFile($filename,$data, $response);

        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals(true, $create);

        $exists = $nextCloudAccessor->getStorage()->fileExists($filename, $response);
        self::assertEquals(207, $response->getStatusCode());
        self::assertEquals(true, $exists);

    }
    public function testDeleteFile() {
        $nextCloudAccessor = AccessorFactory::createNextCloudAccessor($this->createNextCloudSettings());

        $response = Response::MakeResponse();

        $filename = "UnitTest" . $this->generateRandomString(). ".md";
        $data = $this->generateRandomString();
        $create = $nextCloudAccessor->getStorage()->createFile($filename,$data, $response);

        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals(true, $create);

        $exists = $nextCloudAccessor->getStorage()->fileExists($filename, $response);
        self::assertEquals(207, $response->getStatusCode());
        self::assertEquals(true, $exists);

        $delete = $nextCloudAccessor->getStorage()->deleteFile($filename, $response);

        self::assertEquals(204, $response->getStatusCode());
        self::assertEquals(true, $delete);

    }

    public function testGetFile() {
        $nextCloudAccessor = AccessorFactory::createNextCloudAccessor($this->createNextCloudSettings());

        $response = Response::MakeResponse();

        $filename = "UnitTest" . $this->generateRandomString(). ".md";
        $data = $this->generateRandomString();
        $create = $nextCloudAccessor->getStorage()->createFile($filename,$data, $response);

        self::assertEquals(201, $response->getStatusCode());
        self::assertEquals(true, $create);

        $exists = $nextCloudAccessor->getStorage()->fileExists($filename, $response);
        self::assertEquals(207, $response->getStatusCode());
        self::assertEquals(true, $exists);

        $fileData = $nextCloudAccessor->getStorage()->getFile($filename, $response);
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals($data, $fileData);
    }
    private function createNextCloudSettings() : AccessorSettings
    {
        $accessorSettings = new AccessorSettings();
        $accessorSettings->setBaseUri(self::NEXTCLOUD_DEMO_INSTANCE_URI)
            ->setUsername(self::NEXTCLOUD_DEMO_INSTANCE_USER)
            ->setPassword(self::NEXTCLOUD_DEMO_INSTANCE_PASS);

        return $accessorSettings;
    }


    private function generateRandomString() : string {
        return strtoupper(base_convert(random_int(100000000,1000000000-1), 10, 36));
    }

}