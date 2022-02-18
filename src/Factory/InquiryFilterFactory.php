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

    public function createInquiryFilter(array $states): InquiryFilter
    {
        return $this->createBlankInquiryFilter()->setStates($states);
    }
}