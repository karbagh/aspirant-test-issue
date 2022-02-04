<?php

namespace App\Services;

use App\Enum\HttpClient\HttpClientMethodEnum;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BaseClientService
{
    protected string $host;
    protected Client $client;

    private function init(): void{
        $this->client = new Client();
    }

    /**
     * @throws ClientException
     * @throws Exception
     */
    protected function get(
        string $endpoint,
        array  $headers = [],
    ) {
        $this->init();

        try {
            return $this->client->sendRequest(
                new Request(HttpClientMethodEnum::GET, "$this->host$endpoint", $headers)
            );
        } catch (ClientException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param string $endpoint
     * @param array $headers
     * @param array $body
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    protected function post(
        string $endpoint,
        array  $headers = [],
        array  $body = [],
    )
    {
        $this->init();

        try {
            return $this->client->sendRequest(
                new Request(HttpClientMethodEnum::POST, "$this->host$endpoint", $headers, $body)
            );
        } catch (ClientException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param string $endpoint
     * @param array $headers
     * @param array $body
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    protected function put(
        string $endpoint,
        array  $headers = [],
        array  $body = [],
    )
    {
        $this->init();

        try {
            return $this->client->sendRequest(
                new Request(HttpClientMethodEnum::PUT, "{$this->getHost()}$endpoint", $headers, $body)
            );
        } catch (ClientException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param string $endpoint
     * @param array $headers
     * @param array $body
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    protected function patch(
        string $endpoint,
        array  $headers = [],
        array  $body = [],
    )
    {
        $this->init();

        try {
            return $this->client->sendRequest(
                new Request(HttpClientMethodEnum::PATCH, "$this->host$endpoint", $headers, $body)
            );
        } catch (ClientException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param string $endpoint
     * @param array $headers
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    protected function delete(
        string $endpoint,
        array  $headers = [],
    )
    {
        $this->init();

        try {
            return $this->client->sendRequest(
                new Request(HttpClientMethodEnum::DELETE, "$this->host$endpoint", $headers)
            );
        } catch (ClientException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @return string|null
     * @throws Exception
     */
    protected function getHost(): ?string
    {
        return $this->host ?? throw new Exception('Source must be string');
    }
}