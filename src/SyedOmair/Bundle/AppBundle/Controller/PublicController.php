<?php
namespace SyedOmair\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PublicController extends Controller
{
    public function signInAction()
    {
        $logger = $this->get('logger');
        $request = $this->get('request');

        $email = $request->get('email');
        $password = $request->get('password');

        $error = '';

        if ($request->isMethod('POST')) {
            $logger->info('password='.$password);
            $api = $this->get('client_service')->authenticate($email, $password);
            $logger->info('authenticate='.json_encode($api));

            $url = $this->generateUrl('app_index');
            $response = new Response(json_encode(array('success' => true, 'url' => $url, 'error_code' => '0')));
        }
        return $response;
    }

    public function registerAction()
    {
        $logger = $this->get('logger');
        $request = $this->get('request');

        $email = $request->get('email');
        $password = $request->get('password');
        $firstName = $request->get('first_name');
        $lastName = $request->get('last_name');

        $logger->info('first='.$firstName);
        $logger->info('last='.$lastName);
        $logger->info('email='.$email);
        $logger->info('password='.$password);
        $error = '';

        if($request->isMethod('POST')) {
            $parameter = array('email'=>$email, 'password'=>$password, 'first_name'=>$firstName, 'last_name'=>$lastName);
            $api = $this->get('client_service')->createUser($parameter);
            $logger->info('createUser'.json_encode($api));
            
            $logger->info('username='.$email);
            $logger->info('password='.$password);
            $api = $this->get('client_service')->authenticate($email, $password);
            $logger->info('authenticate='.json_encode($api));

            $url = $this->generateUrl('app_index');
            $response = new Response(json_encode(array('success' => true, 'url' => $url, 'error_code' => '0')));
        }
        return $response;
    }
}
