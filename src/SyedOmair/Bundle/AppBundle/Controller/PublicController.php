<?php
namespace SyedOmair\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PublicController extends Controller
{
    public function signOutAction()
    {
        $session = $this->get('session');
        $session->remove('username');
        $session->remove('password');
        return $this->redirectToRoute('app_index');
    }
    public function signInAction()
    {
        $logger = $this->get('logger');
        $request = $this->get('request');

        $email = $request->get('email');
        $password = $request->get('password');

        $errorCode = '0';
        $success = 'true';

        if ($request->isMethod('POST')) {
            $logger->info('password='.$password);
            $api = $this->get('client_service')->authenticate($email, $password);
            if($api['status']=='200'){
               $success = 'true';
            }else{
               $success = 'false';
            }
            $logger->info('authenticate='.json_encode($api));

            $url = $this->generateUrl('app_index');
            $response = new Response(json_encode(array('success' => $success, 'url' => $url, 'error_code' => $errorCode)));
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

        $errorCode = '0';
        $success = 'true';
        if($request->isMethod('POST')) {
            $parameter = array('email'=>$email, 'password'=>$password, 'first_name'=>$firstName, 'last_name'=>$lastName);
            $api = $this->get('client_service')->createUser($parameter);
            if($api['status']=='200'){
               $logger->info('createUser=>'.json_encode($api));
               $success = 'true';
            }else{
               $logger->info('createUser=>'.json_encode($api));
               $errorCode = $api['data']['code'];
               $success = 'false';
            }
            
            $logger->info('username='.$email);
            $logger->info('password='.$password);
            $api = $this->get('client_service')->authenticate($email, $password);
            $logger->info('authenticate='.json_encode($api));

            $url = $this->generateUrl('app_index');
            $response = new Response(json_encode(array('success' => $success, 'url' => $url, 'error_code' => $errorCode)));
        }
        return $response;
    }
}
