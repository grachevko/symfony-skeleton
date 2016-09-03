<?php

namespace Uuid;

use Ramsey\Uuid\UuidInterface;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class Uuid extends \Ramsey\Uuid\Uuid
{
    public function __toString()
    {
        return $this->getBytes();
    }

    /**
     * @return UuidInterface
     */
    public static function create() : UuidInterface
    {
        return static::uuid1();
    }
}
