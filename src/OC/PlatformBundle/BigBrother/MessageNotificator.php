<?php

namespace OC\PlatformBundle\BigBrother;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MessageNotificator
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function warning($message, UserInterface $user)
    {
       $warning = "Message à l'utilisateur ".
                $user->getUsername().
                " Attention, il y a le mot caca dans votre annonce: ".$message;

        $request = $this->requestStack->getCurrentRequest();
        $request->getSession()->getFlashbag()->add('notice', $warning);
    }
}