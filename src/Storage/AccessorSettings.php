<?php
/**
 * Created by PhpStorm.
 * User: icy
 * Date: 15/10/2018
 * Time: 21:00
 */

namespace mistericy\CloudAccess\Storage;


class AccessorSettings
{
    /** @var string */
    private $username;
    /** @var string */
    private $password;
    /** @var string */
    private $baseUri;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return AccessorSettings
     */
    public function setUsername(string $username): AccessorSettings
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return AccessorSettings
     */
    public function setPassword(string $password): AccessorSettings
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    /**
     * @param string $baseUri
     * @return AccessorSettings
     */
    public function setBaseUri(string $baseUri): AccessorSettings
    {
        $this->baseUri = $baseUri;
        return $this;
    }
}