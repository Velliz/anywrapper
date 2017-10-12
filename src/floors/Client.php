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
 * @since    Version 0.1.1
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
    private $api_server;
    private $redirect;
    private $login;

    #region floors tokenize
    private $token = null;
    private $secure = null;
    private $app = null;
    #end region floors tokenize

    private $id = 0;
    private $key = '123456789';
    private $method = 'AES-256-CBC';

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
        $this->identifier = $server['identifier'];
        $this->server = $server['server'];
        $this->api_server = $server['server'] . '/api/';
        $this->login = $server['server'] . '/resume';

        $this->guzzle = new Guzzle([
            'base_uri' => $this->api_server,
            'timeout' => 2.0,
        ]);

        $this->redirect = $redirect;

        if (isset($_GET['secure'])) {
            $this->secure = $_GET['secure'];
        } else {
            $this->secure = $this->GetSession('f_secure');
        }

        if (isset($_GET['token'])) {
            $this->token = $_GET['token'];
        } else {
            $this->token = $this->GetSession('f_token');
        }

        if (isset($_GET['app'])) {
            $this->app = $_GET['app'];
        } else {
            $this->app = $this->GetSession('f_app');
        }

        if ($this->token === null) {
            header('Location: ' . $this->server);
        }
        if ($this->token === '') {
            header('Location: ' . $this->server);
        }
    }

    public function StartSession($redirect = true, $expired = 0)
    {
        if ($this->secure != null && $this->app != null && $this->token != null) {
            $json = $this->Login($this->app, $this->secure);

            $this->PutSession($this->identifier, $json, $expired);
            $this->PutSession('f_token', $this->token, $expired);
            $this->PutSession('f_secure', $this->secure, $expired);
            $this->PutSession('f_app', $this->app, $expired);

            if ($redirect) {
                header('Location: ' . $this->redirect);
            }
        }
    }

    public function GetSessionData()
    {
        return json_decode($this->GetSession($this->identifier), true);
    }

    public function GetTokenData()
    {
        return $this->GetSession('f_token');
    }

    public function IsLogin()
    {
        if ($this->GetSession($this->identifier) !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function Logout($expired)
    {
        $this->RemoveSession($this->identifier, $expired);
        $this->RemoveSession('f_token', $expired);
        $this->RemoveSession('f_secure', $expired);
        $this->RemoveSession('f_app', $expired);
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
        $profile_string = $this->api_server . "avatar/%s/%s";
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
        return json_decode($this->GetSession($this->identifier), true)['id'];
    }

    public function GetSessionName()
    {
        return json_decode($this->GetSession($this->identifier), true)['name'];
    }

    public function GetSessionEmail()
    {
        return json_decode($this->GetSession($this->identifier), true)['email'];
    }

    public function GetLoginUrl()
    {
        return $this->login;
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

    public function PutSession($key, $val, $expired = 0)
    {
        setcookie($key, $this->Encrypt($val), (time() + $expired), "/");
        $_COOKIE[$key] = $this->Encrypt($val);
    }

    public function GetSession($val)
    {
        if (!isset($_COOKIE[$val])) {
            return false;
        }
        return $this->Decrypt($_COOKIE[$val]);
    }

    public function RemoveSession($key, $expired)
    {
        setcookie($key, '', (time() - $expired), '/');
        $_COOKIE[$key] = '';
    }

    private function Encrypt($string)
    {
        $key = hash('sha256', $this->key);
        $iv = substr(hash('sha256', $this->identifier), 0, 16);
        $output = openssl_encrypt($string, $this->method, $key, 0, $iv);
        return base64_encode($output);
    }

    private function Decrypt($string)
    {
        $key = hash('sha256', $this->key);
        $iv = substr(hash('sha256', $this->identifier), 0, 16);
        return openssl_decrypt(base64_decode($string), $this->method, $key, 0, $iv);
    }


}