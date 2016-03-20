<?php
namespace SyedOmair\Bundle\AppBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;

class ApiClientService extends BaseClientService
{

    public function __construct($backend_api_server_end_point, Session $session)
    {
        parent::__construct($backend_api_server_end_point, $session);
    }

    public function findCategories($catalog_id)
    {
        return $this->getRequest('api/categories/'.$catalog_id);
    }

    public function findProducts($category_id)
    {
        return $this->getRequest('api/products/'.$category_id);
    }

    public function findProduct($product_id)
    {
        return $this->getRequest('api/product/'.$product_id);
    }

    public function createUser($parameters)
    {
        return $this->postNewRequest('api/user', $parameters);
    }

    public function authenticate($username, $password)
    {
        $this->session->set('username', $username);
        $this->session->set('password', $password);
        return $this->getRequest('api/authenticate');
    }
}
