<?php

namespace App\Business\Service;

use App\Entity\Inquiry\InquiryAttachment;
use App\Repository\Interfaces\IRepository;
use App\Repository\Interfaces\IInquiryAttachmentRepository;

/**
 * Service class which handles Inquiry attachments.
 * @extends  AService<InquiryAttachment, int>
 */
class InquiryAttachmentService extends AService
{
    /** @var IInquiryAttachmentRepository */
    public IRepository $repository;

    public function __construct(IInquiryAttachmentRepository $attachmentRepository)
    {
        parent::__construct($attachmentRepository);
    }

    public function readByHash(string $hash): InquiryAttachment
    {
        return $this->repository->findOneBy(["hash" => $hash]);
    }
}