<?php

namespace App\Twig\Extension;

use App\Business\Service\UserService;
use App\Entity\Company;
use App\Entity\User;
use App\Enum\Entity\UserType;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UserExtension extends AbstractExtension
{
    public function __construct(private UserService $userService)
    {
    }

    public function getFilters()
    {
        return [
            new TwigFilter('user_name', [$this, 'fullName']),
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

    public function companyAddress(Company $company)
    {
        return $company->getAddressStreet() . ", " . $company->getAddressCity() . " " . $company->getAddressZipCode();
    }
}