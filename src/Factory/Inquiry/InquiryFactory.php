<?php

namespace App\Factory\Inquiry;

use App\Entity\Inquiry\Inquiry;

class InquiryFactory
{
    public function createBlank(): Inquiry
    {
        return new Inquiry();
    }
}