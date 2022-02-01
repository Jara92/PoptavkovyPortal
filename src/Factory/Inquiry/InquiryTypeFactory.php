<?php

namespace App\Factory\Inquiry;

use App\Entity\Inquiry\InquiryType;

class InquiryTypeFactory
{
    /**
     * Create a new inquiry type.
     * @param string $title
     * @param string $alias
     * @return InquiryType
     */
    public function createInquiryType(string $title, string $alias): InquiryType
    {
        $type = new InquiryType();
        $type->setTitle($title)->setAlias($alias);

        return $type;
    }
}