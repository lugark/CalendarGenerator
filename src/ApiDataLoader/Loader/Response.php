<?php

namespace App\ApiDataLoader\Loader;

/**
 * @codeCoverageIgnore
 */
class Response
{
    /**
     * @param array<mixed> $data
     */
    public function __construct(
        private readonly bool $success,
        private readonly int $responseCode,
        private readonly string $response,
        private readonly array $data
    ) {
    }

    /**
     * Get the value of data
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * Get the value of response
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * Get the value of responseCode
     */
    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    /**
     * Get the value of success
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }
}
