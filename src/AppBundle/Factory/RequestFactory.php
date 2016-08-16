<?php

namespace AppBundle\Factory;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Konstantin Grachev <ko@grachev.io>
 */
final class RequestFactory
{
    /**
     * @param Request $request
     * @param $class
     *
     * @return \AppBundle\Request\Request
     */
    public function create(Request $request, $class)
    {
        return new $class($request->query->all(), $request->request->all(), $request->files->all());
    }
}
