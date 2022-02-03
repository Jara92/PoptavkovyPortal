<?php

namespace App\Factory\Inquiry;

use App\Entity\Inquiry\InquiryType;

class InquiryTypeFactory
{
    /**
     * Create a new inquiry type.
     * @param string $title
     * @param string $alias
     * @param int $ordering
     * @return InquiryType
     */
    public function createInquiryType(string $title, string $alias, int $ordering): InquiryType
    {
        $type = new InquiryType();
        $type->setTitle($title)->setAlias($alias)->setOrdering($ordering);

        return $type;
    }
}