<?php

namespace App\Http\Sync;

/**
 * Class Curl
 * @package App\Http\Sync
 */
class Curl
{

    /**
     * Exec
     * @return array
     */
    public function exec()
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => "http://ip-api.com/json/?lang=zh-CN",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
        ]);
        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return ['error' => "cURL Error #: " . $err];
        }
        return json_decode($response, true);
    }

}
