<?php

namespace App\Entity\Inquiry;

use App\Entity\Traits\AliasTrait;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\OrderingTrait;
use App\Entity\Traits\TitleTrait;
use App\Repository\InquiryTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=InquiryTypeRepository::class)
 */
class InquiryType
{
   const ALIAS_PERSONAL = "personal";
   const ALIAS_COMPANY = "company";

   use IdTrait;

    use TitleTrait;

    use AliasTrait;

    use OrderingTrait;
}
