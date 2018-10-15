<?php
/**
 * Created by PhpStorm.
 * User: icy
 * Date: 15/10/2018
 * Time: 21:21
 */

namespace mistericy\CloudAccess\Storage;


class Response
{
    /** @var int */
    private $statusCode;

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return Response
     */
    public function setStatusCode(int $statusCode): Response
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return Response
     */
    public function setBody(string $body): Response
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return Response
     */
    public function setHeaders(array $headers): Response
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return Response
     */
    public function setData(array $data): Response
    {
        $this->data = $data;
        return $this;
    }
    /** @var string */
    private $body;
    /** @var array */
    private $headers;
    /** @var array */
    private $data;

    public function __construct(
        int $statusCode = 200,
        string $body = null,
        array $headers = [],
        array $data = []
    )
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->headers = $headers;
        $this->data = $data;
    }

    public static function MakeResponse(
        int $statusCode = 200,
        string $body = null,
        array $headers = [],
        array $data = []
    ) : Response
    {
        return new self($statusCode, $body, $headers, $data);
    }
}