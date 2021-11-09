<?php

namespace App\Controller;

use App\Services\InquiryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InquiryController extends AbstractController
{
    protected $inquiryService;

    public function __construct(InquiryService $inquiryService)
    {
        $this->inquiryService = $inquiryService;
    }
}