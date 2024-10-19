<?php

namespace wrapper\anywhere;

use Exception;

class Spreadsheet extends Wrapper
{

    /**
     * Pdf constructor.
     * @param $requestType
     * @param null $requestUrl
     */
    public function __construct($requestType, $requestUrl = null)
    {
        parent::__construct();
        $this->requestType = $requestType;
        $this->requestUrl = $requestUrl;
    }

    protected function Init()
    {
        $this->mode = Wrapper::PDF;
    }

    /**
     * @param $key
     * @param $value
     * @throws Exception
     */
    public function setValue($value)
    {
        $this->jsonData['tables'] = $value;
    }

    /**
     * @param $key
     * @param $value
     * @throws Exception
     */
    public function setHeader($value)
    {
        $this->jsonData['header'] = $value;
    }

    /**
     * @param $key
     * @param $value
     * @throws Exception
     */
    public function setFooter($value)
    {
        $this->jsonData['footer'] = $value;
    }

    public function Send($apiUrl, $do_die = true)
    {
        $this->apiUrl = $apiUrl;

        $post['jsondata'] = json_encode($this->jsonData);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->apiUrl);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Anywhere Wrapper');

        if ($do_die) {
            ob_start();
            $response = curl_exec($curl);
            curl_close($curl);

            header("Cache-Control: no-cache");
            header("Pragma: no-cache");
            header("Author: Anywhere 0.1");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            echo $response;
            die();
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}
