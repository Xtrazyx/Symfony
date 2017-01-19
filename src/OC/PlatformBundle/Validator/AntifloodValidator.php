<?php

namespace OC\PlatformBundle\Validator;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AntifloodValidator extends ConstraintValidator
{
    private $requestStack;
    private $em;

    public function __construct(RequestStack $requestStack, EntityManager $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        //on récupère la requête avec le service request_stack
        $request = $this->requestStack->getCurrentRequest();
        $ip = $request->getClientIp();

        // Ceci est un fix car la méthode est incomplète
        $isFlood = false;

        // $isFlood = $this->em
        //    ->getRepository('OCPlatformBundle:Application')
        //    ->isFlood($ip, 15)
        //    ;

        if ($isFlood) {
            $this->context->addViolation($constraint->message);
        }
    }
}