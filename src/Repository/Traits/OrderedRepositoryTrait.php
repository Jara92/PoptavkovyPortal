<?php

namespace App\Repository\Traits;

use Doctrine\ORM\QueryBuilder;

trait OrderedRepositoryTrait
{
    public function orderBy(QueryBuilder $queryBuilder, string $alias, array $ordering = []): QueryBuilder
    {
        foreach ($ordering as $key => $value) {
            $queryBuilder->addOrderBy($alias . "." . $key, $value);
        }

        return $queryBuilder;
    }
}