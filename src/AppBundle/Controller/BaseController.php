<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
abstract class BaseController extends Controller implements TranslatorInterface, NormalizerInterface
{
    public function trans($id, array $parameters = [], $domain = null, $locale = null): string
    {
        return $this->getTranslator()->trans($id, $parameters, $domain, $locale);
    }

    public function transChoice($id, $number, array $parameters = [], $domain = null, $locale = null): string
    {
        return $this->getTranslator()->transChoice($id, $number, $parameters, $domain, $locale);
    }

    public function setLocale($locale): void
    {
        $this->getTranslator()->setLocale($locale);
    }

    public function getLocale(): string
    {
        return $this->getTranslator()->getLocale();
    }

    private function getTranslator(): TranslatorInterface
    {
        return $this->container->get('translator');
    }

    public function normalize($data, $format = null, array $context = [])
    {
        return $this->container->get('serializer')->normalize($data, $format, $context);
    }

    public function supportsNormalization($data, $format = null)
    {
        return true;
    }

    /**
     * @deprecated Don't use controller as service locator
     */
    public function get($id)
    {
        throw new \BadMethodCallException('Inject service instead of use service locator');
    }
}
