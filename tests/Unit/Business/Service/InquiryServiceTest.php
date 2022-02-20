<?php

namespace App\Tests\Unit\Business\Service;

use App\Business\Service\InquiryService;
use App\Repository\Interfaces\Inquiry\IInquiryIRepository;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

class InquiryServiceTest extends TestCase
{
    /** @var IInquiryIRepository */
    protected IInquiryIRepository $repository;

    protected InquiryService $service;

    protected ObjectManager $em;

    function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createStub(IInquiryIRepository::class);
        $this->em = $this->createStub(ObjectManager::class);

        $this->service = new InquiryService($this->repository);
        $this->service->setObjectManager($this->em);
    }
}
