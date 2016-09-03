<?php

namespace AppBundle\Monolog;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class UuidProcessor
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param $token
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        if ($request = $this->requestStack->getMasterRequest()) {
            $record['extra']['token'] = $request->server->get('REQUEST_UUID');
        }

        return $record;
    }
}
