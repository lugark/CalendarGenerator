<?php

namespace App\ApiDataLoader\Loader;

trait CurlLoaderTrait
{
    /** @var CurlRequest */
    protected $client;

    public function __construct(CurlRequest $client)
    {
        $this->client = $client;   
    }

    protected function executeCurl(string $url, array $options): Response
    {
        $this->client->init($url);
        $this->client->setOptions($options);

        $result = $this->client->exec();

        if ($this->client->getLastErrorCode() !== 0) {
            $this->client->close();
            return new Response(false, $this->client->getLastErrorCode(), $this->client->getLastError(), []);
        }
        $responseCode = $this->client->getInfo(CURLINFO_RESPONSE_CODE);
        $this->client->close();

        return $this->getValidatedResponse($responseCode, $result);
    }

    private function getValidatedResponse(int $responseCode, string $result): Response
    {
        if ($responseCode !== 200) {
            return new Response(false, $responseCode, $result, []);
        }

        $status = true;
        $data = json_decode($result, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $data = [];
            $result = json_last_error_msg();
            $responseCode = json_last_error();
            $status = false;
        }

        return new Response($status, $responseCode, $result, $data);
    }
}