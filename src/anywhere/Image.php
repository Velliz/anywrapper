<?php
/**
 * anywhere wrapper
 *
 * Class Wrapper and library for used with OAAS Anywhere
 *
 * Copyright (c) 2016, Didit Velliz
 *
 * @package    anywrapper
 * @author    Didit Velliz
 * @link    https://github.com/velliz/pukoframework
 * @since    Version 0.0.1
 *
 */

namespace wrapper\anywhere;

/**
 * Class AnywhereImage
 * @package anywrapper
 */
class Image extends Wrapper
{

    protected function Init()
    {
        $this->mode = Wrapper::IMAGES;
    }

    public function setImageContentUrl($imgUrl)
    {
        $this->jsonData['url'] = $imgUrl;
    }

    /**
     * @param $apiUrl
     * @return void
     */
    public function Send($apiUrl)
    {
        $this->apiUrl = $apiUrl;
        $post['jsondata'] = json_encode($this->jsonData);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->apiUrl);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Anywhere Wrapper');

        ob_start();
        $response = curl_exec($curl);
        curl_close($curl);

        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        header("Author: Anywhere 0.1");
        header('Content-Type: image/png');

        echo $response;
        die();

    }
}
