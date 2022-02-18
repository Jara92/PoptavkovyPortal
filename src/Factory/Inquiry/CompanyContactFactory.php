<?php

namespace App\Factory\Inquiry;

use App\Entity\Inquiry\CompanyContact;

class CompanyContactFactory
{
    public function createBlankCompanyContact(): CompanyContact
    {
        return new CompanyContact();
    }

    public function createCompanyContact(string $companyName, string $identificationNumber): CompanyContact
    {
        $companyContact = new CompanyContact();

        return $companyContact->setCompanyName($companyName)->setIdentificationNumber($identificationNumber);
    }
}