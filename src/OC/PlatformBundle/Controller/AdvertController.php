<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    public function indexAction($page){
        if($page < 1) {
            throw new NotFoundHttpException('Page '.$page.' inexistante ! Oups.');
        }
        return $this->render('OCPlatformBundle:Advert:index.html.twig');
    }

    public function addAction($id, Request $request){
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('info','Annonce enregistrée !');
            return $this->redirectToRoute('oc_platform_view', array('id' => $id));
        }

        return $this->render('OCPlatformBundle:Advert:add.html.twig');
    }

    public function viewAction($id, Request $request){
        $tag = $request->query->get('tag');
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'id' => $id,
            'tag' => $tag,
        ));
    }
}