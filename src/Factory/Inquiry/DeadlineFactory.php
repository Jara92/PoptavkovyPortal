<?php

namespace App\Factory\Inquiry;

use App\Entity\Inquiry\Deadline;

class DeadlineFactory
{
    /**
     * Create a new deadline.
     * @param string $title
     * @param string $alias
     * @param int $ordering
     * @return Deadline
     */
    public function createDeadline(string $title, string $alias, int $ordering = 0): Deadline
    {
        $deadline = new Deadline();
        $deadline->setTitle($title)->setAlias($alias)->setOrdering($ordering);

        return $deadline;
    }
}