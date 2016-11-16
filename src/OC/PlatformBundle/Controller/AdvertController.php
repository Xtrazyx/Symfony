<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdvertController extends Controller
{
    public function indexAction()
    {
        $content = $this->get('templating')->render('OCPlatformBundle:Advert:index.html.twig', array('nom' => 'Julien'));
        return new Response($content);
    }
    
    public function autreAction()
    {
        return new Response('Autre Action, autre Route, autre twig ? Non pas de twig là dessus');
    }
}