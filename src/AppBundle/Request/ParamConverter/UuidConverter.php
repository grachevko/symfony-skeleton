<?php

namespace AppBundle\Request\ParamConverter;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class UuidConverter implements ParamConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $key = $configuration->getName();
        $value = $request->attributes->get($key);

        if (!Uuid::isValid($value)) {
            throw new BadRequestHttpException(sprintf('Request field "%s" with value "%s" is not valid Uuid', $key, $value));
        }

        $request->attributes->set($key, Uuid::fromString($value));
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === UuidInterface::class;
    }
}
