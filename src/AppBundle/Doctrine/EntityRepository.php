<?php

namespace AppBundle\Doctrine;

use AppBundle\Exception\InvalidArgumentException;
use AppBundle\Traits\Aware\EntityManagerTrait;
use Doctrine\ORM\EntityNotFoundException;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Konstantin Grachev <ko@grachev.io>
 */
abstract class EntityRepository
{
    use EntityManagerTrait;

    /**
     * @return string Entity Class
     */
    abstract protected function getClass(): string;

    /**
     * @param UuidInterface $id
     */
    public function find(UuidInterface $id)
    {
        return $this->em->createQueryBuilder()
            ->select('c')
            ->from($this->getClass(), 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id, 'uuid_binary')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param UuidInterface $id
     *
     * @return mixed
     *
     * @throws EntityNotFoundException
     */
    public function get(UuidInterface $id)
    {
        if (null === $entity = $this->find($id)) {
            throw new EntityNotFoundException();
        }

        return $entity;
    }

    /**
     * @param $entity
     */
    public function save($entity)
    {
        $class = $this->getClass();

        if (!$entity instanceof $class) {
            throw new InvalidArgumentException();
        }

        $this->em->persist($entity);
        $this->em->flush($entity);
    }
}
