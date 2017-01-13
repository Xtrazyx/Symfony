<?php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Form\AdvertType;
use OC\PlatformBundle\Form\AdvertEditType;
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
        $nbPerPage = 4; // On pourra gérer ça avec un paramètre
        $listAdverts = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCPlatformBundle:Advert')
            ->getAdverts($page,$nbPerPage);

        $nbPages = ceil(count($listAdverts) / $nbPerPage);

        if ($nbPages < $page) {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts,
            'page' => $page,
            'nbPages' => $nbPages
        ));
    }

    // Ajout d'un article
    public function addAction(Request $request){

        $advert = new Advert();

        // On crée un form builder grâce au service form factory et on génère le formulaire
        $form = $this->createForm(AdvertType::class, $advert);

        // On teste la méthode POST
        if($request->isMethod('POST')){
            // On lie les variables de la requête du formulaire avec celle de l'objet Advert
            $form->handleRequest($request);
            // On valide les données avant de les enregistrer
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($advert);
                $em->flush();

                $request->getSession()->getFlashbag()->add('notice', 'Annonce enregistrée.');
                return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
            }
        }

        return $this->render('OCPlatformBundle:Advert:add.html.twig', array('form' => $form->createView()));
    }

    // Suppression d'un article
    public function deleteAction(Request $request, $id){
        $manager = $this->getDoctrine()->getManager();
        $advert = $manager->getRepository('OCPlatformBundle:Advert')->find($id);

        if(null === $advert){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $manager->remove($advert);
            $manager->flush();

            $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

            return $this->redirectToRoute('oc_platform_home');
        }

        return $this->render('OCPlatformBundle:Advert:delete.html.twig', array(
            'advert' => $advert,
            'form'   => $form->createView()
        ));
    }

    // Modification d'un article
    public function editAction($id, Request $request){
        $manager = $this->getDoctrine()->getManager();
        $advert = $manager->getRepository('OCPlatformBundle:Advert')->find($id);

        if(null === $advert){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        // On crée un form builder grâce au service form factory et on génère le formulaire
        $form = $this->get('form.factory')->create(AdvertEditType::class, $advert);

        // On teste la méthode POST
        if($request->isMethod('POST')){
            // On lie les variables de la requête du formulaire avec celle de l'objet Advert
            $form->handleRequest($request);
            // On valide les données avant de les enregistrer
            if($form->isValid()){
                $manager->flush();

                $request->getSession()->getFlashbag()->add('notice', 'Annonce modifiée.');
                return $this->redirectToRoute('oc_platform_view', array('id' => $advert->getId()));
            }
        }

        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
            'form' => $form->createView(),
            'advert' => $advert));
    }

    // Menu
    public function menuAction($limit){
        $listAdverts = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCPlatformBundle:Advert')
            ->getLastAdvertsWithLimit($limit);

        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
            'listAdverts' => $listAdverts
        ));
    }

    public function purgeAction($days){
        $this->get('oc_platform.purger.advert')->purge($days);
        return $this->redirectToRoute('oc_platform_home');
    }

    // Affichage des articles
    public function viewAction($id){

        // On appelle le manager de doctrine (service)
        $manager = $this->getDoctrine()->getManager();

        // On cherche via repository l'annonce par son id
        $advert = $manager->getRepository('OCPlatformBundle:Advert')->find($id);

        if (null == $advert){
            throw new NotFoundHttpException("L'annonce ".$id." n'existe pas. Désolé.");
        }

        // On récupère la liste des candidatures pour cette annonce
        // $listApplications = $manager->getRepository('OCPlatformBundle:Application')
        // ->findBy(array('advert' => $advert));

        //On récupère les skills de l'annonce
        // $listAdvertSkills = $manager->getRepository('OCPlatformBundle:AdvertSkill')
        // ->findBy(array('advert' => $advert));

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert' => $advert,
            'flash'
        ));
    }
}