<?php

namespace OC\PlatformBundle\Email;

use OC\PlatformBundle\Entity\Application;

class ApplicationMailer {
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer){
        $this->mailer = $mailer;
    }

    public function sendNewNotification(Application $application){
        $message = new \Swift_Message(
            'Nouvelle candidature',
            'Vous avez reçu une nouvelle candidature !'
        );

        // On récupère ici l'auteur de l'annonce en lieu et place du mail pour le moment
        $message->addTo($application->getAdvert()->getAuthor())->addFrom('adresse_sender@plop.com');

        // On envoi le tout
        $this->mailer->send($message);
    }
}