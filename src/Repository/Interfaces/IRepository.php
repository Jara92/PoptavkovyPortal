<?php

namespace App\Repository\Interfaces;

/**
 * @template E
 * @template K
 */
interface IRepository
{
    /**
     * @param class-string<K>$id
     * @param null $lockMode
     * @param null $lockVersion
     * @return E Entity.
     */
    public function find($id, $lockMode = null, $lockVersion = null);

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return E Entity.
     */
    public function findOneBy(array $criteria, array $orderBy = null);

    /**
     * @return E[]
     */
    public function  findAll();

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     * @return E[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);
}