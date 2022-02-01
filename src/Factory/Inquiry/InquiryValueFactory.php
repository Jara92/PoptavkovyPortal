<?php

namespace App\Factory\Inquiry;

use App\Entity\Inquiry\InquiryValue;

class InquiryValueFactory
{
    /**
     * Create a new inquiry value.
     * @param string $title
     * @param int $value
     * @return InquiryValue
     */
    public function createInquiryValue(string $title, int $value): InquiryValue
    {
        $inquiryValue = new InquiryValue();
        return $inquiryValue->setTitle($title)->setValue($value);
    }
}