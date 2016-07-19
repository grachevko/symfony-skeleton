<?php

namespace AppBundle\EventListener;

use AppBundle\Exception\ValidateException;
use AppBundle\Request\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Konstantin Grachev <ko@grachev.io>
 */
final class RequestListener implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private static $fieldsMap = [
        'since' => 'dateTime',
        'until' => 'dateTime',
        'pageIndex' => 'int',
        'pageSize' => 'int',
    ];

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        foreach ($request->attributes->all() as $key => $value) {
            if (is_string($value) && Uuid::isValid($value)) {
                $request->attributes->set($key, Uuid::fromString($value));
            }
        }

        foreach ($request->query->all() as $key => $value) {
            if (array_key_exists($key, self::$fieldsMap)) {
                $method = 'cast'.ucfirst(self::$fieldsMap[$key]);

                $request->query->set($key, is_array($value) ? array_map([$this, $method], $value) : $this->{$method}($value));
            }
        }

        $controller = $event->getController();
        $method = new \ReflectionMethod($controller[0], $controller[1]);

        foreach ($method->getParameters() as $parameter) {
            if (is_subclass_of($requestClass = (string) $parameter->getType(), Request::class, true)) {
                $requestObject = new $requestClass($request);

                $errors = $this->validator->validate($requestObject);
                if (count($errors)) {
                    throw new ValidateException($errors);
                }

                $request->attributes->set($parameter->getName(), $requestObject);

                break;
            }
        }
    }

    /**
     * @param $string
     *
     * @return \DateTime
     */
    protected function castDateTime($string)
    {
        try {
            return new \DateTime($string, new \DateTimeZone('UTC'));
        } catch (\Exception $e) {
            return $string;
        }
    }

    /**
     * @param $string
     *
     * @return int
     */
    protected function castInt($string)
    {
        return is_numeric($string) && false === strpos($string, ',') && false === strpos($string, '.') ? (int) $string : $string;
    }
}
