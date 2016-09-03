<?php

namespace AppBundle\Traits;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
trait PropertyAssignTrait
{
    /**
     * @param array $parameters
     */
    protected function assign(array $parameters)
    {
        foreach ($parameters as $property => $value) {
            if (!property_exists($this, $property)) {
                continue;
            }

            $this->{$property} = $value;
        }
    }
}
