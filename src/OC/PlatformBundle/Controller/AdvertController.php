<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    // HOME
    public function indexAction($page){
        if ($page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }
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
        return $this->render('OCPlatformBundle:Advert:index.html.twig', array('listAdverts' => $listAdverts));
    }

    // Ajout d'un article
    public function addAction($id, Request $request){
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('info','Annonce enregistrée !');
            return $this->redirectToRoute('oc_platform_view', array('id' => $id));
        }
        return $this->render('OCPlatformBundle:Advert:add.html.twig');
    }

    // Suppression d'un article
    public function deleteAction($id){
        return $this->render('OCPlatformBundle:Advert:delete.html.twig', array ('id' => $id));
    }

    // Modification d'un article
    public function editAction($id, Request $request){
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('info','Annonce n°'.$id.' modifiée !');
            return $this->redirectToRoute('oc_platform_view', array('id' => $id));
        }
        $advert = array(
            'title'   => 'Recherche développpeur Symfony',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()
        );
        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
            'advert' => $advert
        ));
    }

    // Menu
    public function menuAction($limit){
        $listAdverts = array(
            array('id' => 4, 'title' => 'Robert est la pour le show !'),
            array('id' => 2, 'title' => 'Mission ... impossible... 2'),
            array('id' => 8, 'title' => 'Stage de gras du taf !'),
        );
        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }

    // Affichage des articles
    public function viewAction($id){
        $advert = array(
            'title'   => 'Recherche développpeur Symfony',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()
        );
        return $this->render('OCPlatformBundle:Advert:view.html.twig', array('advert' => $advert));
    }
}