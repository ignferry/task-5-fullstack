<?php

namespace App\Http\Helper;

class RequestApi
{
    const API_URL = "http:/task-5-fullstack.test/api/V1/";

    public static function callAPI($method, $url, $data = [], $auth = false) {
        $ch = curl_init(self::API_URL . $url);

        switch($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, 1);
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_PUT, 1);
                if ($data) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            default:
                curl_setopt($ch, CURLOPT_HTTPGET, 1);
                if ($data) {
                    $url = sprintf("%s?%s", $url, http_build_query($data));
                }
        }

        $header = ["Accept: application/json"];
        if ($auth) {
            array_push($header, "Authorization: Bearer ");
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        curl_close($ch);

        return json_decode($response, true);
    }
}