<?php

namespace App\Enum\Entity;

enum InquiryState: string
{
    case STATE_NEW = "new";
    case STATE_ACTIVE = "active";
    case STATE_PROCESSING = "processing";
    case STATE_ARCHIVED = "archived";
    case STATE_FINISHED = "finished";
    case STATE_DELETED = "deleted";
}