<?php

namespace AppBundle\Serializer\Normalizer;

use GuzzleHttp\Promise\PromiseInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class PromiseNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @param PromiseInterface $object
     * @param null             $format
     * @param array            $context
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return $this->normalizer->normalize($object->wait(), $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof PromiseInterface;
    }
}
