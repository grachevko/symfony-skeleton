<?php

namespace AppBundle\Traits;

use AppBundle\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
trait ValidatorTrait
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

    /**
     * @param $value
     * @param null $constraints
     * @param null $groups
     *
     * @throws ValidatorException
     */
    protected function validateThenThrow($value, $constraints = null, $groups = null)
    {
        if (!$this->validator instanceof ValidatorInterface) {
            throw new \RuntimeException('Is need to set Validator first');
        }

        $errors = $this->validator->validate($value, $constraints, $groups);
        if (count($errors)) {
            throw new ValidatorException($errors);
        }

        return $value;
    }
}
