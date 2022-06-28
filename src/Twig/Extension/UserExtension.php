<?php

namespace App\Twig\Extension;

use App\Business\Service\UserService;
use App\Entity\Company;
use App\Entity\User;
use App\Enum\Entity\UserType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UserExtension extends AbstractExtension
{
    public function __construct(
        private UserService         $userService,
        private TranslatorInterface $translator
    )
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('user_name', [$this, 'fullName']),
            new TwigFilter('user_anonymize', [$this, 'anonymize']),
            new TwigFilter('company_address', [$this, 'companyAddress']),
        ];
    }

    /**
     * Returns full name of the user.
     * @param User $user
     * @return string
     */
    public function fullName(User $user): string
    {
        switch ($user->getType()) {
            case UserType::PERSON:
                return $user->getPerson()->getName() . " " . $user->getPerson()->getSurname();
            case UserType::COMPANY:
                return $user->getCompany()->getName();
            default:
                throw new \LogicException("Invalid userType: " . $user->getType()->value);
        }
    }

    /**
     * Returns user's anonymized name.
     * @param User $user
     * @return string
     */
    public function anonymize(User $user): string
    {
        switch ($user->getType()) {
            case UserType::PERSON:
                return $user->getPerson()->getName() . " " . mb_substr($user->getPerson()->getSurname(), 0, 1) . ".";
            case UserType::COMPANY:
                return $this->translator->trans("user.company_from") . " " . $user->getCompany()->getAddressCity();
            default:
                throw new \LogicException("Invalid userType: " . $user->getType()->value);
        }
    }

    public function companyAddress(Company $company): string
    {
        return $company->getAddressStreet() . ", " . $company->getAddressCity() . " " . $company->getAddressZipCode();
    }
}