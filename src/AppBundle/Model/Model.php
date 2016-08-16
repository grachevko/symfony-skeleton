<?php

namespace AppBundle\Model;

use AppBundle\Traits\PropertyAssignTrait;
use AppBundle\Traits\PropertyGetterTrait;

/**
 * @author Konstantin Grachev <ko@grachev.io>
 */
abstract class Model
{
    use PropertyAssignTrait;
    use PropertyGetterTrait;

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->assign($parameters);
    }
}
