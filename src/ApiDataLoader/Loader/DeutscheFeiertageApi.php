<?php

namespace App\ApiDataLoader\Loader;

class DeutscheFeiertageApi implements LoaderInterface
{
    const LOADER_TYPE = 'deutsche_feiertage_api';
    const DEUTSCHE_FEIERTAGE_URL = 'https://deutsche-feiertage-api.de/api/v1/';

    public function fetch(string $year): Response
    {
        $ch = curl_init(self::DEUTSCHE_FEIERTAGE_URL . $year);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'X-DFA-Token: dfa')
        );

        $result = curl_exec($ch);

        if (curl_errno($ch) !== 0) {
            curl_close($ch);
            return new Response(false, curl_errno($ch), curl_error($ch), []);
        }
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        $data = json_decode($result, true);        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new Response(false, json_last_error(), json_last_error_msg(), []);
        }

        return new Response(true, $responseCode, $result, $data);
    }

    public function getType(): String
    {
        return self::LOADER_TYPE;
    }
}