<?php

namespace AppBundle\Doctrine;

use Doctrine\ORM\EntityManager;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
trait EntityManagerTrait
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }
}
