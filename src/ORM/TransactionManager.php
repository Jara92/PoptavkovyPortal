<?php

namespace App\ORM;

use Doctrine\ORM\EntityManagerInterface;

class TransactionManager implements ITransactionManager
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @inheritdoc
     */
    public function beginTransaction(): void
    {
        $this->entityManager->beginTransaction();
    }

    /**
     * @inheritdoc
     */
    public function commit(): void
    {
        $this->entityManager->commit();
    }

    /**
     * @inheritdoc
     */
    public function transactionRollback(): void
    {
        $this->entityManager->rollback();
    }
}