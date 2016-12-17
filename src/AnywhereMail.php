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
namespace anywrapper;

use Exception;

/**
 * Class AnywhereMail
 * @package anywrapper
 */
class AnywhereMail extends Wrapper
{

    public function __construct($requestType, $requestUrl = null)
    {
        parent::__construct();
        $this->requestType = $requestType;
        $this->requestUrl = $requestUrl;
    }

    /**
     * @param $destinationEmail
     * @throws Exception
     */
    public function setTo($destinationEmail)
    {
        if ($destinationEmail == null)
            throw new Exception('Destination email not set.');
        $this->jsonData['to'] = $destinationEmail;
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

    /**
     * @param $cc
     * @throws Exception
     */
    public function setCc($cc)
    {
        if ($cc == null)
            throw new Exception('CC not set.');
        $this->jsonData['cc'] = $cc;
    }

    /**
     * @param $cc
     * @throws Exception
     */
    public function setBcc($cc)
    {
        if ($cc == null)
            throw new Exception('BCC not set.');
        $this->jsonData['bcc'] = $cc;
    }

    /**
     * @param $subject
     * @throws Exception
     */
    public function setSubject($subject)
    {
        if ($subject == null)
            throw new Exception('Subject email not set.');
        $this->jsonData['subject'] = $subject;
    }

    /**
     * @param $replyEmail
     * @throws Exception
     */
    public function setReplyTo($replyEmail)
    {
        if ($replyEmail == null)
            throw new Exception('Reply email not set.');
        $this->jsonData['replyto'] = $replyEmail;
    }

    /**
     * @param $fileName
     * @param $fileUrl
     * @throws Exception
     */
    public function setAttachment($fileName, $fileUrl)
    {
        if ($fileName == null)
            throw new Exception('File name not set.');
        if ($fileUrl == null)
            throw new Exception('File url not set.');

        array_push($this->attachmentData, array(
            'name' => $fileName,
            'url' => $fileUrl
        ));
    }

    protected function Init()
    {
        $this->mode = Wrapper::EMAIL;
    }

    /**
     * @param $apiUrl
     * @return mixed|void
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
            curl_setopt($curl, CURLOPT_TIMEOUT, 1);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
            curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
            curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
            curl_exec($curl);
            curl_close($curl);
        }
    }
}