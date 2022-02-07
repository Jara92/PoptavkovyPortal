<?php

namespace App\Factory;

use App\Filter\InquiryFilter;

class InquiryFilterFactory
{
    /**
     * @return InquiryFilter
     */
    public function createBlankInquiryFilter(): InquiryFilter
    {
        return new  InquiryFilter();
    }
}