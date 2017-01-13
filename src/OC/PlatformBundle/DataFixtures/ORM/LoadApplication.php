<?php

namespace OC\PlatforrmBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Application;

class LoadApplication extends AbstractFixture implements OrderedFixtureInterface {
    public function load(ObjectManager $manager){
        $applications = array(
            array (
                'Manu 2.0',
                'C\'est moi qui serait le meilleur pour ce poste regarder mon cv en ligne !',
                'adv0'
            ),
            array (
                'App_Killer',
                'Le code ça me connait gogogo ! J\'assure partout',
                'adv1'
            ),
            array (
                'Bobby Jean-Jacques',
                'Graphiste confirmé pour design d\'icônes',
                'adv3'
            ),
            array (
                'Jacky',
                'Ceci est une candidature que vous ne pourrez pas ignorer très longtemps, je suis la solution',
                'adv2'
            )
        );

        foreach ($applications as list ($author,$content,$advertRef)){
            $application = new Application();
            $application->setAuthor($author);
            $application->setContent($content);
            $advert = $this->getReference($advertRef);
            $advert->addApplication($application);
            $manager->persist($advert);
            $manager->persist($application);
        }

        $manager->flush();

    }

    public function getOrder(){
        return 4;
    }
}