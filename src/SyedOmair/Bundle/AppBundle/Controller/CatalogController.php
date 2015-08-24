<?php
namespace SyedOmair\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CatalogController extends Controller
{
    public function indexAction(){

        $api = $this->get('client_service')->findCategories('1');
        $categories = $api['data']['records'];

        $api = $this->get('client_service')->findProducts('1');
        $products = $api['data']['records'];

        return $this->render('AppBundle:Catalog:index.html.twig',
                array(
                    'categories' => $categories, 
                    'products' => $products 
                ));
    }

    public function getProductListAction()
    {
        $request = $this->getRequest();
        $category_id = $request->get('category_id');

        $api = $this->get('client_service')->findProducts($category_id);
        $products = $api['data']['records'];

        return $this->render('AppBundle:Catalog:product_list.html.twig',
                array(
                    'products' => $products 
                ));
    }

    public function getCatalogAction($catalog_id)
    {
    }

}