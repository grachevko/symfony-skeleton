<?php

namespace AppBundle\Request;

use AppBundle\Traits\PropertyAssignTrait;
use AppBundle\Traits\PropertyGetterTrait;

/**
 * @property \Symfony\Component\HttpFoundation\Request $request
 *
 * @author Konstantin Grachev <ko@grachev.io>
 */
abstract class Request
{
    use PropertyGetterTrait;
    use PropertyAssignTrait;

    protected static $defaults = [];

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function handleRequest(\Symfony\Component\HttpFoundation\Request $request)
    {
        $this->request = $request;

        $this->assign(array_merge(static::$defaults, $request->query->all()));
    }
}
