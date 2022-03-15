<?php

namespace App\Security\Voter;

use App\Entity\Inquiry\Inquiry;
use App\Enum\Entity\InquiryState;
use App\Entity\User;
use App\Enum\Entity\UserRole;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class InquiryVoter extends AVoter
{
    const REACT = 'react';
    const VIEW_ATTACHMENTS = "view_attachments";

    protected function supports(string $attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::CREATE, self::DELETE, self::REACT, self::VIEW_ATTACHMENTS])) {
            return false;
        }

        // only vote on `Inquiry` objects
        if (!$subject instanceof Inquiry) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $this->security->getUser();

        // SuperUser can do anything.
        if ($this->security->isGranted(UserRole::SUPER_ADMIN)) {
            return true;
        }

        // $subject is an Inquiry object, thanks to `supports()`
        /** @var Inquiry $inquiry */
        $inquiry = $subject;

        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate($user);
            case self::DELETE:
                return $this->canDelete($inquiry, $user);
            case self::VIEW:
                return $this->canView($inquiry, $user);
            case self::EDIT:
                return $this->canEdit($inquiry, $user);
            case self::REACT:
                return $this->canReact($inquiry, $user);
            case self::VIEW_ATTACHMENTS:
                return $this->canViewAttachments($inquiry, $user);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canCreate(?User $user): bool
    {
        // Everyone should be able to create an inquiry.
        return true;
    }

    private function canDelete(Inquiry $inquiry, ?User $user): bool
    {
        // None can do this.
        return false;
    }

    private function canEdit(Inquiry $inquiry, ?User $user): bool
    {
        // The use must be logged in.
        if(!$this->security->isLoggedIn()){
            return false;
        }

        // this assumes that the Inquiry object has a `getOwner()` method
        return $user === $inquiry->getAuthor();
    }

    private function canView(Inquiry $inquiry, ?User $user): bool
    {
        // if they can edit, they can view
        if ($this->canEdit($inquiry, $user)) {
            return true;
        }

        // Inquiry is visible for public.
        return $inquiry->getState() === InquiryState::STATE_ACTIVE;
    }

    private function canReact(Inquiry $inquiry, ?User $user): bool
    {
        // User cannot make offers on his own inquiries.
        if ($user && $user === $inquiry->getAuthor()) {
            return false;
        }

        // If user cannot view, he absolutely cannot react.
        if (!$this->canView($inquiry, $user)) {
            return false;
        }

        // Only suppliers are able to react.
        return $this->security->isGranted(UserRole::SUPPLIER);
    }

    private function canViewAttachments(Inquiry $inquiry, ?User $user): bool
    {
        // Author is able to view attachments.
        if ($user && $user === $inquiry->getAuthor()) {
            return true;
        }

        // If user cannot view the inquiry, he absolutely cannot view attachments.
        if (!$this->canView($inquiry, $user)) {
            return false;
        }

        // Only suppliers are able to view attachments..
        return $this->security->isGranted(UserRole::SUPPLIER);
    }
}