<?php

namespace App\Helper;

use App\Enum\Entity\InquiryState;

class InquiryStateHelper
{
    const INQUIRY_STATE_PREFIX = "inquiry_state.";

    /**
     * Returns inquiry states visible for public.
     * @return array
     */
    public static function getPublicStates(): array
    {
        return [InquiryState::STATE_ACTIVE, InquiryState::STATE_FINISHED, InquiryState::STATE_ARCHIVED];
    }

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