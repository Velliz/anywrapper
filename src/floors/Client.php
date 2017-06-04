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

        $this->token = isset($_GET['token']) ? $_GET['token'] : null;
        $this->app = isset($_GET['app']) ? $_GET['app'] : null;

        if ($this->token != null && $this->app != null) {
            $json = $this->Login($this->app, $this->token);
            $_SESSION[$this->identifier] = (array)json_decode($json);
        }

    }

    public function GetSessionData()
    {
        return $_SESSION[$this->identifier];
    }

    public function IsLogin()
    {
        if (isset($_SESSION[$this->identifier])) {
            return true;
        } else {
            return false;
        }
    }

    public function Logout()
    {
        unset($_SESSION[$this->identifier]);
    }

    private function Login($a, $t)
    {
        $key = hash('sha256', $a);
        $iv = substr(hash('sha256', $this->identifier), 0, 16);
        $json = openssl_decrypt(base64_decode($t), 'AES-256-CBC', $key, 0, $iv);
        return $json;
    }

}