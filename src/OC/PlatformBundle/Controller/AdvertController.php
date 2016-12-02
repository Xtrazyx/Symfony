<?php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
                'date'    => new \Datetime()),
            array(
                'title'   => 'Offre de stage web marketer',
                'id'      => 4,
                'author'  => 'Julien',
                'content' => 'Nous proposons un poste pour web marketer. Blabla…',
                'date'    => new \Datetime()),
            array(
                'title'   => 'Directeur d\'agence web',
                'id'      => 5,
                'author'  => 'Jean-Jacques',
                'content' => 'Nous proposons un poste de directeur d\'agence. Blabla…',
                'date'    => new \Datetime())
        );
        return $this->render('OCPlatformBundle:Advert:index.html.twig', array('listAdverts' => $listAdverts));
    }

    // Ajout d'un article
    public function addAction(Request $request){
        // On récupère l'entity manager
        $em = $this->getDoctrine()->getManager();

        // On instancie notre entité
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony');
        $advert->setContent('Recherche de blabla');
        $advert->setAuthor('Robert Jean-Jacques');

        // Entité image
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Image Job de rêve');

        // On lie l'image à l'annonce
        $advert->setImage($image);

        // Création d'une première candidature
        $application1 = new Application();
        $application1->setAuthor('Marine');
        $application1->setContent("J'ai toutes les qualités requises.");

        // Création d'une deuxième candidature par exemple
        $application2 = new Application();
        $application2->setAuthor('Pierre');
        $application2->setContent("Je suis très motivé.");

        //On lie à l'annonce aux candidatures
        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        //On récupère la liste des skills disponible
        $listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll();

        foreach ($listSkills as $skill){
            $advertSkill = new AdvertSkill();
            $advertSkill->setAdvert($advert);
            $advertSkill->setSkill($skill);
            $advertSkill->setLevel('Expert');
            $em->persist($advertSkill);
        }

        // Doctrine va faire persister notre entity
        $em->persist($advert);
        $em->persist($image);
        $em->persist($application1);
        $em->persist($application2);
        // Doctrine enregistre tout ce qui a été persisté dans la base
        $em->flush();

        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('info','Annonce enregistrée !');
            return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
        }
        return $this->render('OCPlatformBundle:Advert:add.html.twig', array('advert' => $advert));
    }

    // Suppression d'un article
    public function deleteAction($id){
        $manager = $this->getDoctrine()->getManager();
        $advert = $manager->getRepository('OCPlatformBundle:Advert')->find($id);

        foreach ($advert->getCategories() as $category){
            $advert->removeCategory($category);
        }

        $manager->flush();

        return $this->render('OCPlatformBundle:Advert:delete.html.twig', array ('id' => $id));
    }

    // Modification d'un article
    public function editAction($id, Request $request){
        $manager = $this->getDoctrine()->getManager();
        $advert = $manager->getRepository('OCPlatformBundle:Advert')->find($id);
        $categories = $manager->getRepository('OCPlatformBundle:Category')->findAll();

        if(null === $advert){
           throw new HttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        foreach ($categories as $category){
            $advert->addCategory($category);
        }

        $manager->flush();

        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
            'advert' => $advert
        ));
    }

    // Menu
    public function menuAction($limit){
        $listAdverts = array(
            array('id' => 1, 'title' => 'Recherche développeur Symfony !'),
            array('id' => 2, 'title' => 'Mission de webmaster'),
            array('id' => 3, 'title' => 'Offre de stage web designer'),
        );
        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
            'listAdverts' => $listAdverts,
            'limit' => $limit
        ));
    }

    // Affichage des articles
    public function viewAction($id){
        // On appelle le manager de doctrine (service)
        $manager = $this->getDoctrine()->getManager();

        // On cherche via repository l'annonce par son id
        $advert = $manager->getRepository('OCPlatformBundle:Advert')
        ->find($id);

        if (null == $advert){
            throw new NotFoundHttpException("L'annonce ".$id." n'existe pas. Désolé.");
        }

        // On récupère la liste des candidatures pour cette annonce
        $listApplications = $manager->getRepository('OCPlatformBundle:Application')
        ->findBy(array('advert' => $advert));

        //On récupère les skills de l'annonce
        $listAdvertSkills = $manager->getRepository('OCPlatformBundle:AdvertSkill')
        ->findBy(array('advert' => $advert));

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert' => $advert,
            'listApplications' => $listApplications,
            'listAdvertSkills' => $listAdvertSkills
        ));
    }
}