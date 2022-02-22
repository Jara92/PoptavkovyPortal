<?php

namespace App\Helper;

use App\Enum\Entity\InquiryType;
use Closure;

class InquiryTypeHelper
{
    const INQUIRY_TYPE_PREFIX = "inquiry_type.";

    /**
     * Map function which returns an associative array [fnKey => fnValue]
     * @param array $array
     * @param Closure $fnKey
     * @param Closure $fnValue
     * @return array
     */
    private static function asociativeArrayMap(array $array, Closure $fnKey, Closure $fnValue): array
    {
        $keys = array_map($fnKey, $array);
        $values = array_map($fnValue, $array);

        return array_combine($keys, $values);
    }

    /**
     * Returns array [translation_key => InquiryType->value]
     * @return array
     */
    public static function translationStringCases(): array
    {
        return self::asociativeArrayMap(
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
        return self::asociativeArrayMap(
            InquiryType::cases(),
            fn(InquiryType $type) => self::INQUIRY_TYPE_PREFIX . $type->value,
            fn(InquiryType $type) => $type);
    }
}