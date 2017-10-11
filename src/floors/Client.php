<?php
/**
 * floors client
 *
 * Client library for used with Floors
 *
 * Copyright (c) 2016, Didit Velliz
 *
 * @package    anywrapper
 * @author    Didit Velliz
 * @link    https://github.com/velliz/floors
 * @since    Version 0.0.1
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

    private $id = 0;

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

        $session_secure = isset($_SESSION['secure']) ? $_SESSION['secure'] : null;
        $session_token = isset($_SESSION['token']) ? $_SESSION['token'] : null;
        $session_app = isset($_SESSION['app']) ? $_SESSION['app'] : null;

        $this->secure = isset($_GET['secure']) ? $_GET['secure'] : $session_secure;
        $this->token = isset($_GET['token']) ? $_GET['token'] : $session_token;
        $this->app = isset($_GET['app']) ? $_GET['app'] : $session_app;
    }

    public function StartSession($redirect = true)
    {
        if ($this->secure != null && $this->app != null && $this->token != null) {
            $json = $this->Login($this->app, $this->secure);

            $_SESSION[$this->identifier] = (array)json_decode($json);
            $_SESSION['token'] = $this->token;
            $_SESSION['secure'] = $this->secure;
            $_SESSION['app'] = $this->app;

            if ($redirect) {
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
        $profile_string = $this->server . "avatar/%s/%s";
        $size = ($size == null) ? "400" : (string)$size;
        return sprintf($profile_string, $this->id, $size);
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
        $response = $this->guzzle->request('POST', 'authorized', [
            'form_params' => [
                'token' => $this->token,
                'sso' => $this->app,
            ]
        ]);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);
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
        $data = json_decode($body, true);
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
        $data = json_decode($body, true);
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
        $data = json_decode($body, true);
        $this->id = $data['data']['user']['id'];
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

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @return string
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

}