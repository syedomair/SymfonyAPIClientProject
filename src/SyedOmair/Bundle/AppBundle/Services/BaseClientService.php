<?php
namespace SyedOmair\Bundle\AppBundle\Services;

use Guzzle\Http\Client;
use SyedOmair\Bundle\AppBundle\Services\Encrypt;
use SyedOmair\Bundle\AppBundle\Services\Header;
use Symfony\Component\HttpFoundation\Session\Session;

class BaseClientService
{

    protected $container;
    protected $session;
    protected $end_point;
    protected $client;

    public function __construct($container, $api_server_end_point, Session $session)
    {
        $this->container = $container;
        $this->end_point = $api_server_end_point;
        $this->session = $session;
        $this->client = new Client($this->end_point);
    }

    public function createToken($username, $encryptedPass) {
        //$nonce = $this->createNonce();
        //$ts = date('c');
        //$digest = base64_encode(sha1($nonce.$ts.$this->apiSecret,true));
        //return sprintf('AuthToken ApiKey="%s", TokenDigest="%s", Nonce="%s", Created="%s", Username="%s", Password="%s"',
        //    $this->apiKey, $digest, $nonce, $ts, $username, base64_encode($encryptedPass));
        return sprintf('AuthToken Username="%s", Password="%s"', $username, $encryptedPass);
    }
    public function getTokenNewUser()
    {
        $username = 'new';
        $encryptedPass = 'new';
        $token = $this->createToken($username, $encryptedPass);
        return $token;
    }
    public function getToken()
    {
        $username = $this->session->get('username');
        $encryptedPass = $this->session->get('password');
        $token = $this->createToken($username, $encryptedPass);
        return $token;
    }  
/*
    protected function tryDeleteRequest($apiType, $apiURL){
        $apiType = $apiType == 'api'?'':$apiType.'/';
        $request = $this->client->delete('v1/'.$apiType.$this->network.'/'.$apiURL, array('custom-auth' => $this->token, 'network' => $this->network) );
        return $this->processRequest($request);
    }
*/
    protected function getRequest($url){
        $request = $this->client->get($url, array('custom-auth' => $this->getToken() ));
        return $this->processRequest($request);
    }
    protected function postNewRequest($url, $parameters){

        $json_parameter = json_encode($parameters);
        $request = $this->client->post($url, array('custom-auth' => $this->getTokenNewUser() ));
        $request->setBody($json_parameter, 'application/json');

        return $this->processRequest($request);
    }
    protected function postRequest($url, $parameters){

        $json_parameter = json_encode($parameters);
        $request = $this->client->post($url, array('custom-auth' => $this->getToken() ));
        $request->setBody($json_parameter, 'application/json');

        return $this->processRequest($request);
    }

/*
        $json_parameter = json_encode($parameters);
        $apiType = $apiType == 'api'?'':$apiType.'/';
        $request = $client->post('api/user', array('custom-auth' => $token));
        $request = $this->client->post('v1/'.$apiType.$this->network.'/'.$apiURL, array('custom-auth' => $this->token, 'network' => $this->network) );
        $request->setBody($json_parameter, 'application/json');
        return $this->processRequest($request);
    }
*//*
    protected function tryPatchRequest($apiType, $apiURL, $parameters){
        $json_parameter = json_encode($parameters);
        $apiType = $apiType == 'api'?'':$apiType.'/';
        $request = $this->client->patch('v1/'.$apiType.$this->network.'/'.$apiURL, array('custom-auth' => $this->token, 'network' => $this->network) );
        $request->setBody($json_parameter, 'application/json');
        return $this->processRequest($request);
    }
    protected function tryPutRequest($apiType, $apiURL, $parameters){
        $json_parameter = json_encode($parameters);
        $apiType = $apiType == 'api'?'':$apiType.'/';
        $request = $this->client->put('v1/'.$apiType.$this->network.'/'.$apiURL, array('custom-auth' => $this->token, 'network' => $this->network) );
        $request->setBody($json_parameter, 'application/json');
        return $this->processRequest($request);
    }
*/
    protected function processRequest($request){

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

        $dataArray = json_decode($responseBody, true);

        return $dataArray;
    }

}
