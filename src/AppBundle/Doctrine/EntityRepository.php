<?php

namespace AppBundle\Doctrine;

use AppBundle\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
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
     *
     * @return mixed
     */
    public function getReference(UuidInterface $id)
    {
        return $this->em->getReference($this->getClass(), $id);
    }

    /**
     * @param array $ids
     *
     * @return \Generator
     */
    public function getReferences(array $ids)
    {
        foreach ($ids as $id) {
            yield $this->getReference($id);
        }
    }

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
     *
     * @throws InvalidArgumentException
     */
    public function save($entity)
    {
        $this->validate($entity);

        $this->em->persist($entity);
        $this->em->flush($entity);
    }

    /**
     * @param $entity
     */
    public function detach($entity)
    {
        $this->validate($entity);

        $this->em->detach($entity);
    }

    /**
     * @param $alias
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder($alias): QueryBuilder
    {
        return $this->em->createQueryBuilder()
            ->select($alias)
            ->from($this->getClass(), $alias);
    }

    /**
     * @param QueryBuilder $qb
     * @param int          $pageSize
     * @param int          $pageIndex
     *
     * @return Pagerfanta
     */
    protected function paginate(QueryBuilder $qb, int $pageSize, int $pageIndex): Pagerfanta
    {
        return (new Pagerfanta(new DoctrineORMAdapter($qb)))
            ->setMaxPerPage($pageSize)
            ->setCurrentPage($pageIndex);
    }

    /**
     * @param       $displayField
     * @param array $orderBy
     *
     * @return array
     */
    public function findAllForChoices($displayField, array $orderBy = [])
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e.id', 'e.'.$displayField);

        foreach ($orderBy as $key => $value) {
            $qb->addOrderBy('e.'.$key, $value);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $entity
     * @param $class
     *
     * @throws InvalidArgumentException
     */
    protected function validate($entity): void
    {
        $class = $this->getClass();
        if (!$entity instanceof $class) {
            throw new InvalidArgumentException(sprintf('Only entity of "%s" available to use in this repository', $class));
        }
    }
}
