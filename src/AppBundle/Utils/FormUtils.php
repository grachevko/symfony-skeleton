<?php

namespace AppBundle\Utils;

use Symfony\Component\Form\FormInterface;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class FormUtils
{
    /**
     * @param FormInterface $form
     *
     * @return array
     */
    public static function getErrorMessages(FormInterface $form, $root = true)
    {
        $name = $form->getName();
        if (!$root) {
            $name = '['.$name.']';
        }

        $errors = [];
        foreach ($form->getErrors() as $key => $error) {
            if ($root) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[$name][] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            /** @var FormInterface $child */
            if (!$child->isValid()) {
                foreach (self::getErrorMessages($child, false) as $childName => $childErrors) {
                    $errors[$name.$childName] = $childErrors;
                }
            }
        }

        return $errors;
    }
}
