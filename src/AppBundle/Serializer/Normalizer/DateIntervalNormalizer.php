<?php

namespace AppBundle\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Vladislav Vlastovskiy <me@vlastv.ru>
 */
class DateIntervalNormalizer implements NormalizerInterface
{
    /**
     * @param \DateInterval $object
     * @param null          $format
     * @param array         $context
     *
     * @return null|string
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $data = $this->format($object);

        return $data === 'P' ? null : $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof \DateInterval;
    }

    /**
     * @param \DateInterval $dateInterval
     *
     * @return string
     */
    private function format(\DateInterval $dateInterval)
    {
        $format = 'P';

        if (0 < $dateInterval->y) {
            $format .= $dateInterval->y.'Y';
        }

        if (0 < $dateInterval->m) {
            $format .= $dateInterval->m.'M';
        }

        if (0 < $dateInterval->d) {
            $format .= $dateInterval->d.'D';
        }

        if (0 < $dateInterval->h || 0 < $dateInterval->i || 0 < $dateInterval->s) {
            $format .= 'T';
        }

        if (0 < $dateInterval->h) {
            $format .= $dateInterval->h.'H';
        }

        if (0 < $dateInterval->i) {
            $format .= $dateInterval->i.'M';
        }

        if (0 < $dateInterval->s) {
            $format .= $dateInterval->s.'S';
        }

        return $format;
    }
}
