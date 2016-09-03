<?php

namespace AppBundle\EventListener;

use AppBundle\Exception\ValidatorException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class ExceptionListener implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $environment;

    /**
     * @param string $environment
     */
    public function __construct($environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ('dev' === $this->environment) {
            return;
        }
        
        $exception = $event->getException();

        $statusCode = Response::HTTP_BAD_REQUEST;
        $headers = [];
        $json = false;

        if ($exception instanceof ValidatorException) {
            $errors = [];
            foreach ($exception->getErrors() as $error) {
                $errors[] = [
                    'code' => $error->getCode(),
                    'message' => $error->getMessage(),
                    'property' => $error->getPropertyPath(),
                ];
            }

            $data = [
                'error' => [
                    'code' => $exception->getCode() ?: $statusCode,
                    'message' => $exception->getMessage(),
                    'errors' => $errors,
                ],
            ];
        } else {
            if ($exception instanceof HttpException) {
                $statusCode = $exception->getStatusCode();
            }

            $data = [
                'error' => [
                    'code' => $exception->getCode() ?: $statusCode,
                    'message' => $exception->getMessage(),
                ],
            ];
        }

        $event->setResponse(new JsonResponse($data, $statusCode, $headers, $json));
    }
}
