<?php

namespace App\Controller;

use App\Business\Service\InquiryAttachmentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mime\FileinfoMimeTypeGuesser;

class InquiryAttachmentsController extends AbstractController
{
    protected InquiryAttachmentService $attachmentService;

    /**
     * @param InquiryAttachmentService $attachmentService
     */
    public function __construct(InquiryAttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function download(string $hash): Response
    {
        // Get the attachment
        $attachment = $this->attachmentService->readByHash($hash);
        if (!$attachment) {
            throw new NotFoundHttpException("Attachment not found.");
        }

        // Check permissions.
        $this->denyAccessUnlessGranted("view_attachments", $attachment->getInquiry());

        // Create a response.
        $response = new BinaryFileResponse($attachment->getPath());
        // Create mimeTypeGuesser instance.
        $mimeTypeGuesser = new FileinfoMimeTypeGuesser();

        // Set the mimetype with the guesser or manually
        if ($mimeTypeGuesser->isGuesserSupported()) {
            // Guess the mimetype of the file according to the extension of the file
            $response->headers->set('Content-Type', $mimeTypeGuesser->guessMimeType($attachment->getPath()));
        } else {
            // Set the mimetype of the file manually, in this case for a text file is text/plain
            $response->headers->set('Content-Type', 'text/plain');
        }

        // Set content disposition inline of the file
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $attachment->getTitle());

        return $response;
    }
}