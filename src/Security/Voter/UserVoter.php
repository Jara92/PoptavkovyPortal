<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Enum\Entity\UserRole;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends AVoter
{

    /**
     * @inheritDoc
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT, self::CREATE, self::DELETE])) {
            return false;
        }

        // only vote on `Inquiry` objects
        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $currentUser = $this->security->getUser();

        // SuperUser can do anything.
        if ($this->security->isGranted(UserRole::SUPER_ADMIN->value)) {
            return true;
        }

        // $subject is an Inquiry object, thanks to `supports()`
        /** @var User $targetUser */
        $targetUser = $subject;

        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate($currentUser);
            case self::DELETE:
                return $this->canDelete($targetUser, $currentUser);
            case self::VIEW:
                return $this->canView($targetUser, $currentUser);
            case self::EDIT:
                return $this->canEdit($targetUser, $currentUser);
        }

        throw new LogicException('This code should not be reached!');
    }

    protected function canCreate(User $user): bool
    {
        return false;
    }

    protected function canDelete(User $target, ?User $user)
    {
        return false;
    }

    protected function canView(User $target, ?User $user)
    {
        // TODO: add more logic later
        return true;
    }

    protected function canEdit(User $target, ?User $user)
    {
        if ($user) {
            return $user->getId() === $target->getId();
        }

        return false;
    }
}