<?php

namespace OC\PlatformBundle\Beta;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class BetaListener
{
    //Notre processeur
    protected $betaHTML;
    //Date de fin de l'affichage beta
    protected $endDate;

    public function __construct(BetaHTMLAdder $betaHTML, $endDate)
    {
        $this->betaHTML = $betaHTML;
        $this->endDate = new \Datetime($endDate);
    }

    public function processBeta(FilterResponseEvent $event)
    {
        $remainingDays = $this->endDate->diff(new \DateTime())->days;

        if ($remainingDays <= 0) {
            return;
        }

        if (!$event->isMasterRequest()){
            return;
        }

        $response = $event->getResponse();

        $response = $this->betaHTML->addBeta($response,$remainingDays);

        $event->setResponse($response);

    }
}