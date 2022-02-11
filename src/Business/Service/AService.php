<?php

namespace App\Business\Service;

use App\Entity\Inquiry\Inquiry;
use App\Repository\Interfaces\IInquiryIRepository;
use App\Repository\Interfaces\IRepository;
use App\Business\Service\Interfaces\ICrudService;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

/**
 * Abstract service which implements basic service features.
 * @template E
 * @template  K
 * @implements ICrudService<E, K>
 */
class AService implements ICrudService
{
    protected IRepository $repository;

    protected ManagerRegistry $doctrine;

    protected ObjectManager $entityManager;

    /**
     * @param ManagerRegistry $doctrine
     * @required
     */
    public function setDoctrine(ManagerRegistry $doctrine){
        $this->doctrine = $doctrine;
        $this->entityManager = $this->doctrine->getManager();
    }

    /**
     * @param IRepository<E, K> $repository
     */
    public function __construct(IRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Read entity by id.
     * @param K $id
     * @return E|null
     */
    public function readById(mixed $id)
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
    public function existsById(mixed $id): bool
    {
        $entity = $this->readById($id);

        if($entity){
            return true;
        }

        return false;
    }

    /**
     * Create a new entity.
     * @param E $entity
     * @return boolean Success.
     */
    public function create(mixed $entity): bool
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
    public function update(mixed $entity): bool
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param K $id
     * @return boolean Success.
     */
    public function deleteById(mixed $id): bool
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
    public function delete(mixed $entity): bool
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        return true;
    }
}