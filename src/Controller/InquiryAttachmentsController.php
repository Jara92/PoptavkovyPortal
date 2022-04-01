<?php

namespace App\Controller;

use App\Business\Service\Inquiry\InquiryAttachmentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mime\FileinfoMimeTypeGuesser;

class InquiryAttachmentsController extends AbstractController
{
    public function __construct(private InquiryAttachmentService $attachmentService)
    {
    }

    public function download(string $alias, int $id): Response
    {
        // Get the attachment
        $attachment = $this->attachmentService->readById($id);
        if (!$attachment) {
            throw new NotFoundHttpException("Attachment not found.");
        }

        if ($attachment->getInquiry()->getAlias() !== $alias) {
            throw new NotFoundHttpException("Invalid inquiry alias");
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