<?php

namespace OC\PlatformBundle\Purger;

use Doctrine\ORM\EntityManager;


class PurgerAdvert
{

    /**
     * @var EntityManager
     */
    private $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function purge($days){
        $em = $this->manager;
        $advertsList = $em
            ->getRepository('OCPlatformBundle:Advert')
            ->getAdvertsWithoutApplication($days)
        ;

        foreach ($advertsList as $advert){
            $em->remove($advert);
        }

        $em->flush();
    }
}