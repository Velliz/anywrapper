<?php

namespace wrapper\floors;

/**
 * Class Client
 * @package wrapper\floors
 */
class Client
{

    private $identifier;
    private $server;

    #region floors tokenize
    private $token = null;
    private $secure = null;
    private $app = null;
    #end region floors tokenize

    private $redirect = '';

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

    public function GetPublicProfileURL()
    {

    }

    public function IsHasPermission($permission)
    {
        $guzzle = new \GuzzleHttp\Client([
            'base_uri' => $this->server,
            'timeout' => 2.0,
        ]);
        $response = $guzzle->request('POST', 'authorized', [
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