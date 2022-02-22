<?php

namespace App\Helper;

use App\Enum\Entity\InquiryState;

class InquiryStateHelper
{
    const INQUIRY_STATE_PREFIX = "inquiry_state.";

    /**
     * Returns array [translation_key => InquiryState->value]
     * @return array
     */
    public static function translationStringCases(): array
    {
        return ArrayHelper::asociativeArrayMap(
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
        return ArrayHelper::asociativeArrayMap(
            InquiryState::cases(),
            fn(InquiryState $state) => self::INQUIRY_STATE_PREFIX . $state->value,
            fn(InquiryState $state) => $state);
    }
}