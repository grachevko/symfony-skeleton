<?php

namespace AppBundle\Controller\v1;

use AppBundle\Controller\BaseController;
use AppBundle\Request\Request;
use Ramsey\Uuid\UuidInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route(service="app.controller.default")
 *
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class DefaultController extends BaseController
{
    /**
     * @Route(name="entity_list")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
    }

    /**
     * @Route("/{id}", name="entity_show")
     * @Method("GET")
     */
    public function showAction(UuidInterface $id)
    {
    }

    /**
     * @Route(name="entity_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
    }

    /**
     * @Route(name="entity_update")
     * @Method("PATCH")
     */
    public function updateAction(Request $request)
    {
    }

    /**
     * @Route(name="entity_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request)
    {
    }
}
