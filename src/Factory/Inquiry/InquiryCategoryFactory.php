<?php

namespace App\Factory\Inquiry;

use App\Entity\Inquiry\InquiryCategory;
use App\Helper\UrlHelper;

class InquiryCategoryFactory
{
    protected function getDefaultAlias(string $title): string
    {
        return UrlHelper::createAlias($title);
    }

    public function createBaseCategory(string $title, string $alias = "", string $description = ""): InquiryCategory
    {
        // Default alias value.
        if ($alias === "") {
            $alias = self::getDefaultAlias($title);
        }

        return (new InquiryCategory())->setTitle($title)->setAlias($alias)->setDescription($description);
    }

    public function createSubCategory(InquiryCategory $parent, string $title, string $alias = "", string $description = ""): InquiryCategory
    {
        // Default alias value.
        if ($alias === "") {
            $alias = self::getDefaultAlias($title);
        }

        return $this->createBaseCategory($title, $parent->getAlias() . "-" . $alias, $description)->setParent($parent);
    }
}