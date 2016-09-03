<?php

use Uuid\Uuid;
use Uuid\UuidBuilder;
use Ramsey\Uuid\Codec\OrderedTimeCodec;
use Ramsey\Uuid\UuidFactory;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
class UuidBootstrap
{
    public static function bootstrap()
    {
        $uuidFactory = new UuidFactory();
        $uuidBuilder = new UuidBuilder($uuidFactory->getNumberConverter());
        $uuidFactory->setUuidBuilder($uuidBuilder);
        $uuidFactory->setCodec(new OrderedTimeCodec($uuidBuilder));

        Uuid::setFactory($uuidFactory);
    }
}

UuidBootstrap::bootstrap();
