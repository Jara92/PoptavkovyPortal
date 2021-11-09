<?php

namespace App\Services;

use App\Entity\Inquiry;
use App\Repository\Interfaces\IRepository;
use App\Services\Interfaces\ICrudService;

/**
 * Abstract service which implements basic service features.
 * @template E
 * @template K
 * @implements ICrudService<E, K>
 */
 class AService implements ICrudService
{

    protected $repository;

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
         // TODO: Implement create() method.
     }

     /**
      * Update an entity.
      * @param E $entity
      * @return boolean Success.
      */
     public function update($entity): bool
     {
         // TODO: Implement update() method.
     }

     /**
      * @param K $id
      * @return boolean Success.
      */
     public function deleteById($id): bool
     {
         // TODO: Implement deleteById() method.
     }
 }