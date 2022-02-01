<?php

namespace App\Factory\Inquiry;

use App\Entity\Inquiry\InquiryState;

class InquiryStateFactory
{
    /**
     * Create a new inquiry state.
     * @param string $title
     * @param string $alias
     * @param string $description
     * @param int $ordering
     * @return InquiryState
     */
    public function createInquiryState(string $title, string $alias, string $description = "", int $ordering = 0): InquiryState
    {
        $state = new InquiryState();
        $state->setTitle($title)->setAlias($alias)->setDescription($description)->setOrdering($ordering);

        return $state;
    }
}