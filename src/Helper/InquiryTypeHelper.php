<?php

namespace App\Helper;

use App\Enum\Entity\InquiryType;

class InquiryTypeHelper
{
    const INQUIRY_TYPE_PREFIX = "inquiry_type.";

    /**
     * Returns array [translation_key => InquiryType->value]
     * @return array
     */
    public static function translationStringCases(): array
    {
        return ArrayHelper::asociativeArrayMap(
            InquiryType::cases(),
            fn(InquiryType $type) => self::INQUIRY_TYPE_PREFIX . $type->value,
            fn(InquiryType $type) => $type->value);
    }

    /**
     * Returns array [translation_key => InquiryType]
     * @return array
     */
    public static function translationCases(): array
    {
        return self::convertToTranslationCases(InquiryType::cases());
    }

    /**
     * Converts $types array to format [translation_key => InquiryType->value]
     * @param InquiryType[] $types
     * @return array
     */
    public static function convertToTranslationCases(array $types): array
    {
        return ArrayHelper::asociativeArrayMap(
            $types,
            fn(InquiryType $type) => self::INQUIRY_TYPE_PREFIX . $type->value,
            fn(InquiryType $type) => $type);
    }
}