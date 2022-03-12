<?php /** @noinspection PhpUnusedAliasInspection */

namespace App\Business\Service\Inquiry;

use App\Business\Service\AService;
use App\Entity\Inquiry\CompanyContact;
use App\Repository\Interfaces\Inquiry\ICompanyContactRepository;
use App\Repository\Interfaces\IRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Service class which handles everything about CompanyContact.
 * @extends  AService<CompanyContact, int>
 */
class CompanyContactService extends AService
{
    /** @var ICompanyContactRepository */
    protected IRepository $repository;

    public function __construct(ICompanyContactRepository $inquiryRepository)
    {
        parent::__construct($inquiryRepository);
    }
}