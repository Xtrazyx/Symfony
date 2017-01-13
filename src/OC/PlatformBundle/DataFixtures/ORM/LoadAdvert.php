<?php

namespace OC\PlatforrmBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Advert;

class LoadAdvert extends AbstractFixture implements OrderedFixtureInterface {
    public function load(ObjectManager $manager){
        $adverts = array(
            array (
                'Recherche Dev Symfony',
                'Bobby 1',
                'Ceci est une annonce pour un job, une annonce de test. N°1',
                array('cat0','cat5')
            ),
            array (
                'Mission web app super top!',
                'Robert 664',
                'Ceci est une annonce pour un job, une annonce de test. N°2',
                array('cat1','cat2','cat4')
            ),
            array (
                'Recherche Lead Designer',
                'Jojo l\'asticot',
                'Ceci est une annonce pour un job, une annonce de test. N°3',
                array('cat3','cat2')
            ),
            array (
                'Graphiste confirmé pour design d\'icônes',
                'Bouya du 93',
                'Ceci est une annonce pour un job, une annonce de test. N°4',
                array('cat2','cat1')
            ),
            array (
                'Lead project manager sur du SAS avec haut profil IT',
                'Insane Web Agency',
                'Venez chez nous, si vous un super crack, vous ne serez pas déçu',
                array('cat0','cat1','cat2','cat3','cat4','cat5')
            ),
            array (
                'Stage de ouebe',
                'Stage Inc.',
                'Les stages c\'est bien, profitez en bien chez nous, vous ferez vos armes !',
                array('cat1')
            ),
            array (
                'Super job',
                'Job_lol.com',
                'Rejoignez nos équipes dans une ambiance déconne studieuse !',
                array('cat4','cat2')
            )
        );

        foreach ($adverts as $nb => list ($title,$author,$content,$categories)){
            $advert = new Advert();
            $advert->setTitle($title);
            $advert->setAuthor($author);
            $advert->setContent($content);
            foreach ($categories as $catRef){
                $advert->addCategory($this->getReference($catRef));
            }
            $manager->persist($advert);
            $advRef = 'adv'.$nb;
            $this->addReference($advRef,$advert);
        }
        $manager->flush();

    }

    public function getOrder(){
        return 3;
    }
}