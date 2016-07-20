<?php

namespace AppBundle\Traits;

use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Konstantin Grachev <ko@grachev.io>
 */
trait ValidatorAwareTrait
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
}
