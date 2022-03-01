<?php

namespace App\ORM;

interface ITransactionManager
{
    /**
     * Starts a new transaction.
     */
    public function beginTransaction(): void;

    /**
     * Commits current transaction.
     */
    public function commit(): void;

    /**
     * Rollbacks current transaction.
     */
    public function transactionRollback(): void;
}