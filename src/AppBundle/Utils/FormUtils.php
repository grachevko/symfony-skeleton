<?php

namespace AppBundle\Utils;

use Ramsey\Uuid\UuidInterface;
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

    /**
     * @param array  $array
     * @param string $displayField
     * @param string $valueField
     *
     * @return array
     */
    public static function arrayToChoices(array $array, string $displayField, string $valueField = 'id'): array
    {
        $result = [];
        foreach ($array as $key => $item) {
            $id = $item[$valueField];

            $display = $item[$displayField];
            $value = $id instanceof UuidInterface ? $id->toString() : $id;

            $result[$display] = $value;
        }

        return $result;
    }
}
