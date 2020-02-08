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
namespace wrapper\anywhere;

/**
 * Class Wrapper
 * @package anywrapper
 */
abstract class Wrapper
{

    const PDF = 1;
    const IMAGES = 2;
    const EMAIL = 3;

    const POST = 9;
    const GET = 8;
    const URL = 7;

    var $mode;

    var $requestType;
    var $requestUrl;

    var $jsonData = array();
    var $creatorInfo = array();
    var $attachmentData = array();

    var $apiUrl;

    public function __construct()
    {
        $this->Init();
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->jsonData;
    }

    /**
     * @return array
     */
    public function getAttachment()
    {
        return $this->attachmentData;
    }

    protected abstract function Init();

    /**
     * @param $apiUrl
     * @return mixed
     */
    public abstract function Send($apiUrl);

}