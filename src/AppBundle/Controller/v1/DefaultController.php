<?php

namespace AppBundle\Controller\v1;

use AppBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route(service="app.controller.default")
 *
 * @author Konstantin Grachev <ko@grachev.io>
 */
final class DefaultController extends Controller
{
    /**
     * @Route("/{url}")
     */
    public function indexAction()
    {
        return Response::HTTP_OK;
    }
}
