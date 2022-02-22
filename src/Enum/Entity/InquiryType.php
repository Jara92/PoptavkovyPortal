<?php

namespace App\Enum\Entity;

enum InquiryType: string
{
    case PERSONAL = "personal";
    case COMPANY = "company";

    /**
     * Returns array [translation_key => InquiryType->value]
     * @return array
     */
    public static function translationCases(): array
    {
        return [
            "inquiry_type.personal" => InquiryType::PERSONAL->value,
            "inquiry_type.company" => InquiryType::COMPANY->value,
        ];
    }
}