<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
abstract class BaseController extends Controller
{
    /**
     * @param $id
     *
     * @return string
     */
    protected function trans($id, array $parameters = [], $domain = null, $locale = null)
    {
        return $this->container->get('translator')->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @param       $data
     * @param null  $format
     * @param array $context
     *
     * @return mixed
     */
    protected function normalize($data, $format = null, array $context = [])
    {
        return $this->container->get('serializer')->normalize($data, $format, $context);
    }

    /**
     * @deprecated
     */
    public function get($id)
    {
        throw new \BadMethodCallException('Inject service instead of use service locator');
    }
}
