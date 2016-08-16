<?php

namespace AppBundle\Request;

use AppBundle\Model\Model;
use AppBundle\Traits\PropertyAssignTrait;
use AppBundle\Traits\PropertyGetterTrait;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
abstract class Request
{
    use PropertyGetterTrait;
    use PropertyAssignTrait;

    protected static $defaults = [];

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var
     */
    protected $files;

    /**
     * @return string
     */
    abstract protected function getModelClass(): string;

    /**
     * @param array $query
     * @param array $request
     * @param array $files
     */
    public function __construct(array $query, array $request = [], array $files = [])
    {
        $this->assign(array_merge(static::$defaults, $query));

        if ($request) {
            $this->setModel($request);
        }

        if ($files) {
            $this->setFiles($files);
        }
    }

    /**
     * @param array $request
     */
    protected function setModel(array $request)
    {
        $class = $this->getModelClass();

        $this->model = $class ? new $class($request) : $request;
    }

    /**
     * @param array $files
     */
    protected function setFiles(array $files)
    {
        $this->files = $files;
    }
}
