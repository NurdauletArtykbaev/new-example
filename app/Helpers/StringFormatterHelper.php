<?php

namespace App\Helpers;


class StringFormatterHelper
{
    public function onlyDigits($str) {
        return preg_replace('/[^0-9]/', '', $str);
    }

    public function maskPhone($phone) {
        $formatted[] = "+".substr($phone, 0, 1);
        $formatted[] = "(".substr($phone, 1, 3).")";
        $formatted[] = substr($phone, 4, 3);
        $formatted[] = "-".substr($phone, 7, 2);
        $formatted[] = "-".substr($phone, 9, 2);

        return implode('', $formatted);
    }

    public function parseXml($data)
    {
        $data = simplexml_load_string($data);

        return json_decode(json_encode($data), false);
    }

    public function parsePayboxErrorHtml($data)
    {
        $result = [];
        preg_match('#\<form[^\>]+?\>(.*?)\<\/form\>#si', $data, $inputs);
        $inputs = $inputs[1];
        preg_match_all('#\<input[^\>]+?\>#si', $inputs, $inputs);
        $inputs = $inputs[0];
        foreach ($inputs as $input) {
            $input = explode('/(<[^>]*[^\/]>)/i', $input);
            $input = $input[0];
            preg_match('#name="(.*?)"#si', $input, $inputName);
            preg_match('#value="(.*?)"#si', $input, $inputValue);
            if ($inputName[1] != '_token') {
                $result[$inputName[1]] =  $inputValue[1];
            }
        }
        return $result;
    }
}
