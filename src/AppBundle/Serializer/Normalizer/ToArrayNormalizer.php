<?php

namespace AppBundle\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class ToArrayNormalizer implements NormalizerInterface
{
    /**
     * @param mixed $object
     * @param null  $format
     * @param array $context
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return $object->toArray();
    }

    /**
     * @param mixed $data
     * @param null  $format
     */
    public function supportsNormalization($data, $format = null)
    {
        return method_exists($data, 'toArray');
    }
}
