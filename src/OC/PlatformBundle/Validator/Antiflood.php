<?php

namespace OC\PlatformBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Antiflood extends Constraint
{
    public $message = "Vous avez déjà posté il y a moins de 15 secondes : STOP FLOOD !";

    public function validatedBy()
    {
        return 'oc_platform_antiflood';
    }
}