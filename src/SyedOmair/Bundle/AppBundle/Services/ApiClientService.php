<?php

namespace SyedOmair\Bundle\AppBundle\Services;

use Guzzle\Http\Client;

class ApiClientService
{
    protected $container;
    protected $end_point;

    public function __construct($container, $api_server_end_point)
    {
        $this->container = $container;
        $this->end_point = $api_server_end_point;
    }

    public function findCategories($catalog_id)
    {
        $client = new Client($this->end_point);
        $request = $client->get('api/categories/'.$catalog_id);
        $response = $request->send();
        $responseBody= $response->getBody();
        $responseArray = json_decode($responseBody, true);
        return $responseArray;
    }

    public function findProducts($category_id)
    {
        $client = new Client($this->end_point);
        $request = $client->get('api/products/'.$category_id);
        $response = $request->send();
        $responseBody= $response->getBody();
        $responseArray = json_decode($responseBody, true);
        return $responseArray;
    }

    public function findProduct($product_id)
    {
        $client = new Client($this->end_point);
        $request = $client->get('api/products/'.$product_id);
        $response = $request->send();
        $responseBody= $response->getBody();
        $responseArray = json_decode($responseBody, true);
        return $responseArray;
    }

}
