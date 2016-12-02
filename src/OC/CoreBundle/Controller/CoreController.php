<?php

namespace OC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller
{
    public function indexAction(){
        // On simule le résultat d'une requête SQL qui récupère les trois dernières annonces
        $listAdverts = array(
            array(
                'title'   => 'Recherche développeur Symfony',
                'id'      => 1,
                'author'  => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
                'date'    => new \Datetime()),
            array(
                'title'   => 'Mission de webmaster',
                'id'      => 2,
                'author'  => 'Hugo',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                'date'    => new \Datetime()),
            array(
                'title'   => 'Offre de stage web designer',
                'id'      => 3,
                'author'  => 'Robert',
                'content' => 'Nous proposons un poste pour web designer. Blabla…',
                'date'    => new \Datetime())
        );
        return $this->render('OCCoreBundle:Core:index.html.twig', array('listAdverts' => $listAdverts));
    }

    public function contactAction(Request $request){
        // Message flash provisoire
        $request->getSession()->getFlashBag()->add('info', 'Nous sommes désolés: Le formulaire de contact sera bientôt disponible.');
        return $this->redirectToRoute('oc_core_home');
    }
}