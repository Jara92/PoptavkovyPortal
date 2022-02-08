<?php

namespace App\Factory;

use App\Tools\Filter\InquiryFilter;

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