<?php

namespace App\Factory\Inquiry;

use App\Entity\Inquiry\Deadline;

class DeadlineFactory
{
    /**
     * Create a new deadline.
     * @param string $title
     * @param string $alias
     * @return Deadline
     */
    public function createDeadline(string $title, string $alias): Deadline
    {
        $deadline = new Deadline();
        $deadline->setTitle($title)->setAlias($alias);

        return $deadline;
    }
}