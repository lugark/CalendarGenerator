<?php

namespace App\ApiDataLoader\Loader;

class Response
{
    /** @var int */
    private $responseCode;
    /** @var string */
    private $response;
    /** @var array */
    private $data; 
    /** @var bool */
    private $success;

    public function __construct(bool $success, int $responseCode, string $response, array $data)
    {
        $this->success = $success;
        $this->responseCode = $responseCode;
        $this->response = $response;
        $this->data = $data;
    }

    /**
     * Get the value of data
     */ 
    public function getData(): array
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
    public function isSuccess()
    {
        return $this->success;
    }
}