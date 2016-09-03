<?php

namespace AppBundle\Request\ParamConverter;

use AppBundle\Request\RequestFactory;
use AppBundle\Traits\ValidatorTrait;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class RequestConverter implements ParamConverterInterface
{
    use ValidatorTrait;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @param RequestFactory $requestFactory
     * @param ValidatorInterface $validator
     */
    public function __construct(RequestFactory $requestFactory, ValidatorInterface $validator)
    {
        $this->requestFactory = $requestFactory;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $requestObject = $this->requestFactory->create($request, $configuration->getClass());

        $this->validateThenThrow($requestObject, null, [$request->getMethod()]);

        $request->attributes->set($configuration->getName(), $requestObject);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        return is_subclass_of($configuration->getClass(), \AppBundle\Request\Request::class, true);
    }
}
