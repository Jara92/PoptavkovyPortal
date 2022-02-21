<?php

namespace App\Security\Voter;

use App\Entity\Profile;
use App\Entity\User;
use App\Entity\UserType;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ProfileVoter extends AVoter
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

        // only vote on `Profile` objects
        if (!$subject instanceof Profile) {
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
        if ($this->security->isGranted(User::ROLE_SUPER_ADMIN)) {
            return true;
        }

        // $subject is an Profile object, thanks to `supports()`
        /** @var Profile $profile */
        $profile = $subject;

        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate($currentUser);
            case self::DELETE:
                return $this->canDelete($profile, $currentUser);
            case self::VIEW:
                return $this->canView($profile, $currentUser);
            case self::EDIT:
                return $this->canEdit($profile, $currentUser);
        }

        throw new LogicException('This code should not be reached!');
    }

    protected function canCreate(Profile $user): bool
    {
        return false;
    }

    protected function canDelete(Profile $profile, ?User $user)
    {
        return false;
    }

    protected function canView(Profile $profile, ?User $user)
    {
        // Use can edit the profile if he can edit it.
        if ($this->canEdit($profile, $user)) {
            return true;
        }

        // Company profiles are public.
        if ($profile->getUser()->isType(UserType::TYPE_COMPANY)) {
            return true;
        }

        return false;
    }

    protected function canEdit(Profile $profile, ?User $user)
    {
        if ($user) {
            return $user === $profile->getUser();
        }

        return false;
    }
}
