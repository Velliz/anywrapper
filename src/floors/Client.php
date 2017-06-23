<?php
/**
 * floors client
 *
 * Client library for used with Floors
 *
 * Copyright (c) 2016, Didit Velliz
 *
 * @package	anywrapper
 * @author	Didit Velliz
 * @link	https://github.com/velliz/floors
 * @since	Version 0.0.1
 *
 */
namespace wrapper\floors;

use \GuzzleHttp\Client as Guzzle;

/**
 * Class Client
 * @package wrapper\floors
 */
class Client
{

    private $guzzle;

    private $identifier;
    private $server;
    private $redirect;

    #region floors tokenize
    private $token = null;
    private $secure = null;
    private $app = null;
    #end region floors tokenize



    /**
     * Client constructor.
     *
     * @param array $server
     * [
     *   'identifier' => '',
     *   'server_url' => '',
     * ]
     *
     * @param string $redirect
     */
    public function __construct($server, $redirect)
    {
        session_start();

        $this->identifier = $server['identifier'];
        $this->server = $server['server'];

        $this->guzzle = new Guzzle([
            'base_uri' => $this->server,
            'timeout' => 2.0,
        ]);

        $this->redirect = $redirect;
    }

    public function StartSession($refresh = true)
    {
        $this->secure = isset($_GET['secure']) ? $_GET['secure'] : $_SESSION['secure'];
        $this->token = isset($_GET['token']) ? $_GET['token'] : $_SESSION['token'];
        $this->app = isset($_GET['app']) ? $_GET['app'] : $_SESSION['app'];

        if ($this->secure != null && $this->app != null && $this->token != null) {
            $json = $this->Login($this->app, $this->secure);

            $_SESSION[$this->identifier] = (array)json_decode($json);
            $_SESSION['token'] = $this->token;
            $_SESSION['secure'] = $this->secure;
            $_SESSION['app'] = $this->app;

            if ($refresh) {
                header('Location: ' . $this->redirect);
            }
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
        $profile_string = $this->server . "api/avatar/%s/%s";
        $size = ($size == null) ? "400" : (string)$size;
        return sprintf($profile_string, $user['id'], $size);
    }

    public function IsHasPermission($permission)
    {
        $response = $this->guzzle->request('POST', 'authorized', [
            'form_params' => [
                'token' => $this->token,
                'sso' => $this->app,
            ]
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body);

        foreach ($data->data->auth as $val) {
            if (strcasecmp($val->pcode, $permission) == 0) {
                return true;
            }
        }
        return false;
    }

    public function GetPermission()
    {
        $response = $this->guzzle->request('POST', 'confirm/password', [
            'form_params' => [
                'token' => $this->token,
            ]
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body);
        return $data;
    }

    public function ConfirmPassword($password, $confirm)
    {
        $response = $this->guzzle->request('POST', 'confirm/password', [
            'form_params' => [
                'token' => $this->token,
                'password' => $password,
                'confirm' => $confirm,
            ]
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body);

        if ($data->status == 'success' && isset($data->data->id)) {
            return true;
        }
        return false;
    }

    public function GetLoginInformation()
    {
        $response = $this->guzzle->request('POST', 'login/info', [
            'form_params' => [
                'token' => $this->token,
            ]
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body);
        return $data;
    }

    public function GetLinkedAccountUsage()
    {
        $response = $this->guzzle->request('POST', 'credential/info', [
            'form_params' => [
                'token' => $this->token,
            ]
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body);
        return $data;
    }

    public function GetUserData()
    {
        $response = $this->guzzle->request('POST', 'user', [
            'form_params' => [
                'token' => $this->token,
            ]
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body);
        return $data;
    }

    public function GetSessionID()
    {
        return $_SESSION[$this->identifier]['id'];
    }

    public function GetSessionName()
    {
        return $_SESSION[$this->identifier]['name'];
    }

    public function GetSessionEmail()
    {
        return $_SESSION[$this->identifier]['email'];
    }

}