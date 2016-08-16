<?php

namespace AppBundle\Request;

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
     * @var
     */
    protected $model;

    /**
     * @var
     */
    protected $files;

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
        throw new \DomainException('Method must be overwritten');
    }

    /**
     * @param array $files
     */
    protected function setFiles(array $files)
    {
        $this->files = $files;
    }
}
