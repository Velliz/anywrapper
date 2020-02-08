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

use Exception;

/**
 * Class AnywherePdf
 * @package anywrapper
 */
class Pdf extends Wrapper
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
            return;
        $this->jsonData[$key] = $value;
    }

    /**
     * @param $key
     * @param $value
     * @throws Exception
     */
    public function setCreator($value)
    {
        if ($value == null)
            return;
        $this->creatorInfo = $value;
    }

    protected function Init()
    {
        $this->mode = Wrapper::PDF;
    }

    /**
     * @param $apiUrl
     * @param bool $do_die
     * @return void
     */
    public function Send($apiUrl, $do_die = true)
    {
        $this->apiUrl = $apiUrl;
        if (count($this->attachmentData) > 0) {
            $this->jsonData['attachment'] = $this->attachmentData;
        }
        $post['jsondata'] = json_encode($this->jsonData);
        $post['creator'] = json_encode($this->creatorInfo);

        if ($this->requestType == Wrapper::POST) {

            header("Cache-Control: no-cache");
            header("Pragma: no-cache");
            header("Author: Anywhere 0.1");
            header('Content-Type: application/pdf');

            ob_start();

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->apiUrl);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Anywhere Wrapper');
            $response = curl_exec($curl);
            curl_close($curl);

            echo $response;

            if ($do_die) {
                die();
            }
        }
        return null;
    }
}