<?php

namespace App\Factory\Inquiry;

use App\Entity\Inquiry\Inquiry;
use App\Entity\Inquiry\InquiryAttachment;

class InquiryAttachmentFactory
{
    public function createAttachment(Inquiry $inquiry, string $title, string $hash, string $description, int $size, string $path, string $type): InquiryAttachment
    {
        $attachment = new InquiryAttachment();
        $attachment->setInquiry($inquiry)->setTitle($title)->setHash($hash)->setDescription($description)->setSize($size)
            ->setPath($path)->setType($type);

        return $attachment;
    }
}