<?php

namespace OC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller
{
    public function indexAction(){
        $listAdverts = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCPlatformBundle:Advert')
            ->getLastAdvertsWithLimit(3); //On met la limite en dur pour l'instant

        return $this->render('OCCoreBundle:Core:index.html.twig', array('listAdverts' => $listAdverts));
    }

    public function contactAction(Request $request){
        // Message flash provisoire
        $request->getSession()->getFlashBag()->add('info', 'Nous sommes désolés: Le formulaire de contact sera bientôt disponible.');
        return $this->redirectToRoute('oc_core_home');
    }
}