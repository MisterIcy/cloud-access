<?php
/**
 * Created by PhpStorm.
 * User: icy
 * Date: 15/10/2018
 * Time: 20:56
 */

namespace mistericy\CloudAccess\Storage;

use mistericy\CloudAccess\Storage\NextCloud\NextCloudAccessor;

abstract class AccessorFactory
{

    public abstract function __construct(AccessorSettings $settings);

    /**
     * @param AccessorSettings $settings
     * @return NextCloudAccessor
     * @throws Exception\AccessorException
     */
    public static function createNextCloudAccessor(AccessorSettings $settings) {
        return new NextCloudAccessor($settings);
    }

    /**
     * @param AccessorSettings $accessorSettings
     * @return AccessorSettings
     */
    abstract protected function validateSettings(AccessorSettings $accessorSettings): AccessorSettings;

    /**
     * Returns the accessor's name
     * @return string The name of the accessor in string format (e.g. nextcloud)
     */
    abstract public function getAccessorName() : string;

    /**
     * Executes a request and returns an universally recognizable response
     * @param string $method
     * @param string $endpoint
     * @param string|null $body
     * @param array $headers
     * @return Response
     */
    abstract public function doRequest(
        string $method,
        string $endpoint,
        string $body = null,
        array $headers = []
    ) : Response;
}