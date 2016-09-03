<?php

namespace AppBundle\EventListener;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Serializer;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
class ResponseListener implements EventSubscriberInterface
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => 'onKernelView',
        ];
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();

        if (!$result) {
            return;
        }

        $data = null;
        $statusCode = Response::HTTP_OK;
        $headers = [];
        $json = false;

        if ($result instanceof UuidInterface) {
            $data = ['id' => (string) $result];
            $statusCode = Response::HTTP_CREATED;
        } elseif (is_numeric($result)) {
            $statusCode = $result;
        } elseif (is_string($result)) {
            $data = $result;
        } else {
            $data = $this->serializer->normalize($result);
        }

        $event->setResponse(new JsonResponse($data, $statusCode, $headers, $json));
    }
}
