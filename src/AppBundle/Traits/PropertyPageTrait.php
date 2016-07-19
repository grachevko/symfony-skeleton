<?php

namespace AppBundle\Traits;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @property int $pageSize
 * @property int $pageIndex
 *
 * @author Konstantin Grachev <ko@grachev.io>
 */
trait PropertyPageTrait
{
    /**
     * @var int
     *
     * @Assert\Type("int")
     */
    protected $pageSize;

    /**
     * @var int
     *
     * @Assert\Type("int")
     */
    protected $pageIndex;
}
