<?php

namespace AppBundle\Traits;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
trait PropertyGetterTrait
{
    /**
     * @param $name
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function __get($name)
    {
        if (!property_exists($this, $name)) {
            throw new \InvalidArgumentException(sprintf('Property "%s" not exist in object instance of "%s"', $name, get_class($this)));
        }

        return $this->{$name};
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
