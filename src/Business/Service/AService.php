<?php

namespace App\Business\Service;

use App\Entity\Inquiry\Inquiry;
use App\Repository\Interfaces\IRepository;
use App\Business\Service\Interfaces\ICrudService;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Abstract service which implements basic service features.
 * @template E
 * @template K
 * @implements ICrudService<E, K>
 */
class AService implements ICrudService
{

    protected $repository;

    /** @var EntityManager */
    protected $doctrine;

    protected $entityManager;

    /**
     * @param IRepository<E, K> $repository
     * @param ManagerRegistry $doctrine
     */
    public function __construct(IRepository $repository, ManagerRegistry $doctrine)
    {
        $this->repository = $repository;
        $this->doctrine = $doctrine;
        $this->entityManager = $this->doctrine->getManager();
    }

    /**
     * Read entity by id.
     * @param K $id
     * @return E|null
     */
    public function readById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Read all saved entities.
     * @return E[]
     */
    public function readAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * Read entities by params.
     * @param array $params
     * @param array|null $orderBy
     * @return E[]
     */
    public function readBy(array $params, array $orderBy = null): array
    {
        return $this->repository->findBy($params, $orderBy);
    }

    /**
     * @param K $id
     * @return boolean True if the entity already exists.
     */
    public function existsById($id): bool
    {
        // TODO: Implement existsById() method.
    }

    /**
     * Create a new entity.
     * @param E $entity
     * @return boolean Success.
     */
    public function create($entity): bool
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return true;
    }

    /**
     * Update an entity.
     * @param E $entity
     * @return boolean Success.
     */
    public function update($entity): bool
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param K $id
     * @return boolean Success.
     */
    public function deleteById($id): bool
    {
        $entity = $this->readById($id);

        if(!$entity){
            // TODO: do something
        }

        return $this->delete($entity);
    }

    /**
     * @param E $entity
     * @return boolean Success.
     */
    public function delete($entity): bool
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return true;
    }
}