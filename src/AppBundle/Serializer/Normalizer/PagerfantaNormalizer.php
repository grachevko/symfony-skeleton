<?php

namespace AppBundle\Serializer\Normalizer;

use Pagerfanta\Pagerfanta;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class PagerfantaNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @param Pagerfanta $object
     * @param null       $format
     * @param array      $context
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'pageIndex' => $object->getCurrentPage(),
            'pageSize' => $object->getMaxPerPage(),
            'totalItems' => $object->getNbResults(),
            'items' => $this->normalizer->normalize(iterator_to_array($object->getCurrentPageResults()), $format, $context),
        ];
    }

    /**
     * @param Pagerfanta $data
     * @param null       $format
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Pagerfanta;
    }
}
