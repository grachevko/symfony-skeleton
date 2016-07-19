<?php

namespace AppBundle\Request;

use AppBundle\Traits\PropertyAssignTrait;
use AppBundle\Traits\PropertyGetterTrait;

/**
 * @author Konstantin Grachev <ko@grachev.io>
 */
abstract class Request
{
    use PropertyGetterTrait;
    use PropertyAssignTrait {
        __construct as __parentConstructor;
    }

    protected static $defaults = [];

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(\Symfony\Component\HttpFoundation\Request $request)
    {
        $parameters = $request->query->all();

        if (static::$defaults) {
            $parameters = array_merge(static::$defaults, $parameters);
        }

        $this->__parentConstructor($parameters);
    }
}
