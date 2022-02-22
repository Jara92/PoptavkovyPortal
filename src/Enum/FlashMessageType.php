<?php

namespace App\Enum;

enum FlashMessageType: string
{
    case SUCCESS = "success";
    case NOTICE = "primary";
    case WARNING = "warning";
    case ERROR = "danger";
}