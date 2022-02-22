<?php

namespace App\Helper;

use App\Enum\Entity\InquiryState;
use Closure;

class InquiryStateHelper
{
    const INQUIRY_STATE_PREFIX = "inquiry_state.";

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
     * Returns array [translation_key => InquiryState->value]
     * @return array
     */
    public static function translationStringCases(): array
    {
        return self::asociativeArrayMap(
            InquiryState::cases(),
            fn(InquiryState $state) => self::INQUIRY_STATE_PREFIX . $state->value,
            fn(InquiryState $state) => $state->value);
    }

    /**
     * Returns array [translation_key => InquiryState]
     * @return array
     */
    public static function translationCases(): array
    {
        return self::asociativeArrayMap(
            InquiryState::cases(),
            fn(InquiryState $state) => self::INQUIRY_STATE_PREFIX . $state->value,
            fn(InquiryState $state) => $state);
    }
}