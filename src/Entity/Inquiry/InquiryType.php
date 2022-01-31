<?php

namespace App\Entity\Inquiry;

use App\Entity\Traits\AliasTrait;
use App\Entity\Traits\IdTrait;
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

    /**
     * @param string $title
     * @param string $alias
     * @return InquiryType
     */
    public static function create(string $title, string $alias)
    {
        $type = new InquiryType();
        $type->setTitle($title)->setAlias($alias);

        return $type;
    }
}
