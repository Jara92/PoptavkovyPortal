<?php

namespace App\Business\Service\Interfaces;

/**
 * CRUD service interface.
 * @template E
 * @template  K
 */
interface ICrudService
{
    /**
     * Read entity by id.
     * @param K $id
     * @return E|null
     */
    public function readById(mixed $id);

    /**
     * Read all saved entities.
     * @return E[]
     */
    public function readAll(): array;

    /**
     * Read entities by params.
     * @param array $params
     * @param array|null $orderBy
     * @return E[]
     */
    public function readBy(array $params, array $orderBy = null): array;

    /**
     * @param K $id
     * @return boolean True if the entity already exists.
     */
    public function existsById(mixed $id): bool;

    /**
     * Create a new entity.
     * @param E $entity
     * @return boolean Success.
     */
    public function create(mixed $entity): bool;

    /**
     * Update an entity.
     * @param E $entity
     * @return boolean Success.
     */
    public function update(mixed $entity): bool;

    /**
     * @param K $id
     * @return boolean Success.
     */
    public function deleteById(mixed $id): bool;
}