<?php

namespace AppBundle\Request;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class RequestFactory
{
    /**
     * @param Request $request
     * @param $class
     *
     * @return Request
     */
    public function create(Request $request, $class)
    {
        return new $class($request->query->all(), $request->request->all(), $request->files->all());
    }
}
