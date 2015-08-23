<?php
namespace SyedOmair\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CatalogController extends Controller
{
    public function indexAction(){

        $api = $this->get('client_service')->findCategories('1');
        var_dump($api);
        return $this->render('AppBundle:Catalog:index.html.twig',
                array( 
                ));
    }

    public function getCatalogAction($catalog_id)
    {
    }
}
