<?php
namespace SyedOmair\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CatalogController extends Controller
{
    public function indexAction(){

        return $this->render('AppBundle:Catalog:index.html.twig',
                array( 
                ));
    }

    public function getCatalogAction($catalog_id)
    {
    }
}
