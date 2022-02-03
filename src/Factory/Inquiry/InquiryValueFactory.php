<?php

namespace App\Factory\Inquiry;

use App\Entity\Inquiry\InquiryValue;

class InquiryValueFactory
{
    /**
     * Create a new inquiry value.
     * @param string $title
     * @param int $value
     * @param int $ordering
     * @return InquiryValue
     */
    public function createInquiryValue(string $title, int $value, int $ordering): InquiryValue
    {
        $inquiryValue = new InquiryValue();
        return $inquiryValue->setTitle($title)->setValue($value)->setOrdering($ordering);
    }
}