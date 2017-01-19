<?php

namespace OC\PlatformBundle\BigBrother;

use OC\PlatformBundle\Event\MessagePostEvent;

class MessageListener
{
    protected $notificator;

    public function __construct(MessageNotificator $notificator)
    {
        $this->notificator = $notificator;
    }

    public function processMessage(MessagePostEvent $event)
    {
        if (preg_match("/(.*) caca (.*)/",$event->getMessage())){
            $this->notificator->warning($event->getMessage(),$event->getUser());
        }
    }
}