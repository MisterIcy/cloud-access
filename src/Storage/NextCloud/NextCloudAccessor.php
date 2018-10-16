<?php
/**
 * Created by PhpStorm.
 * User: icy
 * Date: 15/10/2018
 * Time: 21:04
 */

namespace mistericy\CloudAccess\Storage\NextCloud;


use mistericy\CloudAccess\Storage\AccessorFactory;
use mistericy\CloudAccess\Storage\AccessorSettings;
use mistericy\CloudAccess\Storage\Exception\AccessorException;
use mistericy\CloudAccess\Storage\Response;
use mistericy\CloudAccess\Storage\StorageInterface;
use Sabre\DAV\Client;
use Sabre\DAV\Xml\Response\MultiStatus;
use Sabre\Xml\ParseException;

final class NextCloudAccessor extends AccessorFactory
{
    const ACCESSOR_NAME = 'nextcloud';

    /** @var AccessorSettings */
    private $accessorSettings;

    /** @var Client */
    private $client;

    /** @var NextCloudStorage */
    private $storage;

    /**
     * @return NextCloudStorage
     */
    public function getStorage(): NextCloudStorage
    {
        return $this->storage;
    }

    /**
     * NextCloudAccessor constructor.
     * @param AccessorSettings $settings
     * @throws AccessorException
     */
    public function __construct(AccessorSettings $settings)
    {
        $this->accessorSettings = $this->validateSettings($settings);

        $settings = [
            "username" => $this->accessorSettings->getUsername(),
            "password" => $this->accessorSettings->getPassword(),
            "baseUri" => $this->accessorSettings->getBaseUri(),
            "authType" => Client::AUTH_DIGEST,
        ];

        $this->client = new Client($settings);

        $this->storage = new NextCloudStorage($this);
    }

    /**
     * @return string
     */
    public function getAccessorName(): string
    {
        return self::ACCESSOR_NAME;
    }

    /**
     * @param AccessorSettings $accessorSettings
     * @return AccessorSettings
     * @throws AccessorException
     */
    protected function validateSettings(AccessorSettings $accessorSettings): AccessorSettings
    {
        if (empty($accessorSettings->getUsername()) ||
            empty($accessorSettings->getPassword()) ||
            empty($accessorSettings->getBaseUri())) {
            throw AccessorException::invalidSettingsForAccessor($this->getAccessorName());
        }
        /** Add a trailing slash to uris that miss it */
        if (substr($accessorSettings->getBaseUri(),-1) !== '/') {
            $accessorSettings->setBaseUri($accessorSettings->getBaseUri() . "/");
        }

        return $accessorSettings;
    }

    public function doRequest(
        string $method,
        string $endpoint,
        string $body = null,
        array $headers = []
    ) : Response
    {
        $this->client->addCurlSetting(CURLOPT_USERNAME, $this->accessorSettings->getUsername());
        $this->client->addCurlSetting(CURLOPT_PASSWORD, $this->accessorSettings->getPassword());

        $davResponse = $this->client->request($method, $this->makeUri($endpoint), $body, $headers);
        return $this->serializeResponse($davResponse);
    }
    private function serializeResponse(array $httpResponse) : Response
    {
        $response = Response::MakeResponse(
            $httpResponse['statusCode'],
            $httpResponse['body'],
            $httpResponse['headers']
        );

        if (empty($httpResponse['body'])) return $response;
        $service = new \Sabre\XML\Service();
        try {
            $parsed = $service->parse($httpResponse['body']);
            if ($parsed instanceof MultiStatus) {
                $response->setData($parsed->getResponses());
            }
        } catch (ParseException $e) {
            $response->setData([]);
        }
        return $response;
    }

    private function makeUri(string $endpoint) : string
    {
        return "remote.php/dav/files/{$this->accessorSettings->getUsername()}/{$endpoint}";
    }


}