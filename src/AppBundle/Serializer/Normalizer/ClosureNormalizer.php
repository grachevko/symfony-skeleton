<?php

namespace AppBundle\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class ClosureNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @param \Closure $object
     * @param null     $format
     * @param array    $context
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return $this->normalizer->normalize($object(), $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof \Closure;
    }
}
