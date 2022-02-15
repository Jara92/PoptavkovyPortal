<?php

namespace App\Enum;

enum FlashMessageType
{
    case SUCCESS;
    case NOTICE;
    case WARNING;
    case ERROR;

    public function alias(): string
    {
        return match ($this) {
            FlashMessageType::SUCCESS => 'success',
            FlashMessageType::NOTICE => 'primary',
            FlashMessageType::WARNING => 'warning',
            FlashMessageType::ERROR => 'danger',
        };
    }
}