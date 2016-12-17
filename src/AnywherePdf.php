<?php
/**
 * anywhere wrapper
 *
 * Class Wrapper and library for used with OAAS Anywhere
 *
 * Copyright (c) 2016, Didit Velliz
 *
 * @package	anywrapper
 * @author	Didit Velliz
 * @link	https://github.com/velliz/pukoframework
 * @since	Version 0.0.1
 *
 */
namespace anywrapper;

use Exception;

/**
 * Class AnywherePdf
 * @package anywrapper
 */
class AnywherePdf extends Wrapper
{

    public function __construct($requestType, $requestUrl = null)
    {
        parent::__construct();
        $this->requestType = $requestType;
        $this->requestUrl = $requestUrl;
    }

    /**
     * @param $key
     * @param $value
     * @throws Exception
     */
    public function setValue($key, $value)
    {
        if ($key == null)
            throw new Exception('Key not set.');
        if ($value == null)
            throw new Exception('Value not set.');
        $this->jsonData[$key] = $value;
    }

    protected function Init()
    {
        $this->mode = Wrapper::PDF;
    }

    /**
     * @param $apiUrl
     */
    public function Send($apiUrl)
    {
        $this->apiUrl = $apiUrl;
        $this->jsonData['attachment'] = $this->attachmentData;
        $post['jsondata'] = json_encode($this->jsonData);

        if ($this->requestType == Wrapper::POST) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->apiUrl);
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Anywhere Wrapper');
            $response = curl_exec($curl);
            curl_close($curl);

            header("Cache-Control: no-cache");
            header("Pragma: no-cache");
            header("Author: Anywhere 0.1");
            header('Content-Type: application/pdf');

            echo $response;
        }
    }
}