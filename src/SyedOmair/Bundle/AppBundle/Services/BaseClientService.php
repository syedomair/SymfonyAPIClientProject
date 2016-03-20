<?php
namespace SyedOmair\Bundle\AppBundle\Services;

use Guzzle\Http\Client;
use Symfony\Component\HttpFoundation\Session\Session;

class BaseClientService
{
    protected $session;
    protected $backend_api_server_end_point;
    protected $client;

    public function __construct($backend_api_server_end_point, Session $session)
    {
        $this->backend_api_server_end_point = $backend_api_server_end_point;
        $this->session = $session;
        $this->client = new Client($this->backend_api_server_end_point);
    }

    public function createCustomToken($username, $encryptedPass) {
        return sprintf('AuthToken Username="%s", Password="%s"', $username, $encryptedPass);
    }

    public function getCustomTokenForNewUser()
    {
        $username = 'new_user_registration';
        $password = 'new_user_registration';
        $token = $this->createCustomToken($username, $password);
        return $token;
    }

    public function getCustomToken()
    {
        $username = $this->session->get('username');
        $password = $this->session->get('password');
        $token = $this->createCustomToken($username, base64_encode($password));
        return $token;
    }  


    protected function deleteRequest($url)
    {
        $request = $this->client->delete($url, array('custom-auth' => $this->getCustomToken() ));
        return $this->processRequest($request);
    }

    protected function getRequest($url)
    {
        $request = $this->client->get($url, array('custom-auth' => $this->getCustomToken() ));
        return $this->processRequest($request);
    }

    protected function postNewRequest($url, $parameters)
    {
        $json_parameter = json_encode($parameters);
        $request = $this->client->post($url, array('custom-auth' => $this->getCustomTokenForNewUser() ));
        $request->setBody($json_parameter, 'application/json');
        return $this->processRequest($request);
    }

    protected function postRequest($url, $parameters)
    {
        $json_parameter = json_encode($parameters);
        $request = $this->client->post($url, array('custom-auth' => $this->getCustomToken() ));
        $request->setBody($json_parameter, 'application/json');
        return $this->processRequest($request);
    }

    protected function patchRequest($url, $parameters)
    {
        $json_parameter = json_encode($parameters);
        $request = $this->client->patch($url, array('custom-auth' => $this->getCustomToken() ));
        $request->setBody($json_parameter, 'application/json');
        return $this->processRequest($request);
    }

    protected function putRequest($url, $parameters)
    {
        $json_parameter = json_encode($parameters);
        $request = $this->client->put($url, array('custom-auth' => $this->getCustomToken() ));
        $request->setBody($json_parameter, 'application/json');
        return $this->processRequest($request);
    }

    protected function processRequest($request)
    {
        $responseBody = '';
        try
        {
            $response = $request->send();
            $responseBody= $response->getBody();
        }
        catch (BadResponseException $e) 
        {
            if ($e->getResponse())
                $responseBody =  $e->getResponse()->getBody();
        }
        return json_decode($responseBody, true);
    }
}
