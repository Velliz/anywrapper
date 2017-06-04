<?php

namespace wrapper\floors;

/**
 * Class Client
 * @package wrapper\floors
 */
class Client
{

    private $identifier = null;
    private $token = null;
    private $app = null;

    public function __construct($identifier)
    {
        session_start();
        $this->identifier = $identifier;
    }

    public function StartSession()
    {
        $this->token = $_GET['token'];
        $this->app = $_GET['app'];

        if ($this->token != null && $this->app != null) {
            $json = $this->Login($this->app, $this->token);
            $_SESSION[$this->app] = (array)json_decode($json);
        }
    }

    public function GetSessionData()
    {
        return $_SESSION[$this->app];
    }

    private function Login($a, $t)
    {
        $key = hash('sha256', $a);
        $iv = substr(hash('sha256', $this->identifier), 0, 16);
        $json = openssl_decrypt(base64_decode($t), 'AES-256-CBC', $key, 0, $iv);
        return $json;
    }

}