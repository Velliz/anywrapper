<?php

namespace wrapper\floors;

/**
 * Class Client
 * @package wrapper\floors
 *
 * TODO: clone it to anywrapper so can downloaded via composer
 */
class Client
{

    private $identifier = null;
    private $token = null;
    private $secure = null;
    private $app = null;

    private $redirect = null;

    public function __construct($identifier, $redirect_url)
    {
        session_start();
        $this->identifier = $identifier;
        $this->redirect = $redirect_url;
    }

    public function StartSession()
    {
        $this->secure = isset($_GET['secure']) ? $_GET['secure'] : null;
        $this->token = isset($_GET['token']) ? $_GET['token'] : null;
        $this->app = isset($_GET['app']) ? $_GET['app'] : null;

        if ($this->secure != null && $this->app != null && $this->token != null) {
            $json = $this->Login($this->app, $this->secure);

            $_SESSION[$this->identifier] = (array)json_decode($json);
            $_SESSION['token'] = $this->token;

            header('Location: ' . $this->redirect);
            exit;
        }

    }

    public function GetSessionData()
    {
        return $_SESSION[$this->identifier];
    }

    public function GetTokenData()
    {
        return $_SESSION['token'];
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

    public function GetProfilePictureURL($size = null)
    {
        $user = $_SESSION[$this->identifier];
        $profile_string = "http://localhost/floors/api/avatar/%s/%s";
        $size = ($size == null) ? "400" : (string)$size;
        return sprintf($profile_string, $user['id'], $size);
    }

    public function GetProfileURL()
    {

    }

    public function IsHasPermission($permission)
    {

    }

    public function GetPermission()
    {

    }

    public function ConfirmPassword()
    {

    }

    public function GetProfileInformation()
    {

    }

    public function GetLinkedAccount()
    {

    }
}