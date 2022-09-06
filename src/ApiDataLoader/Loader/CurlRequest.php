<?php

namespace App\ApiDataLoader\Loader;

class CurlRequest implements RequestInterface
{
    private $ch;

    private function init(string $url): CurlRequest
    {
        $this->ch = curl_init($url);
        return $this;
    }

    public function setOptions(array $options): CurlRequest
    {
        curl_setopt_array($this->ch, $options);
        return $this;
    }

    public function setOption($option, $value): CurlRequest
    {
        curl_setopt($this->ch, $option, $value);
        return $this;
    }

    private function close(): CurlRequest
    {
        curl_close($this->ch);
        return $this;
    }

    private function getInfo(string $curlInfoType)
    {
        return curl_getinfo($this->ch, $curlInfoType);
    }

    private function getLastErrorCode(): int
    {
        return curl_errno($this->ch);
    }

    private function getLastError(): string
    {
        return curl_error($this->ch);
    }

    private function call()
    {
        return curl_exec($this->ch);
    }

    public function execute(string $url, array $options): Response
    {
        $this->init($url);
        $this->setOptions($options);

        $result = $this->call();

        if ($this->getLastErrorCode() !== 0) {
            $this->close();
            return new Response(false, $this->getLastErrorCode(), $this->getLastError(), []);
        }
        $responseCode = $this->getInfo(CURLINFO_RESPONSE_CODE);
        $this->close();

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
