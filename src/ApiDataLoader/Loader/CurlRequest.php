<?php

namespace App\ApiDataLoader\Loader;

class CurlRequest
{
    private $ch;

    public function init(string $url): CurlRequest
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
   
    public function exec()
    {
        return curl_exec($this->ch);
    }

    public function close(): CurlRequest
    {
        curl_close($this->ch);
        return $this;
    }

    public function getInfo(string $curlInfoType)
    {
        return curl_getinfo($this->ch, $curlInfoType);
    }

    public function getLastErrorCode(): int
    {
        return curl_errno($this->ch);
    }

    public function getLastError(): string
    {
        return curl_error($this->ch);
    }
}