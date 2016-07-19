<?php

namespace AppBundle\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @author Konstantin Grachev <ko@grachev.io>
 */
final class ValidatorException extends \RuntimeException
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $errors;

    /**
     * @param $errors
     */
    public function __construct(ConstraintViolationListInterface $errors, $message = 'Bad Request', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errors = $errors;
    }
    /**
     * @return ConstraintViolationListInterface
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
