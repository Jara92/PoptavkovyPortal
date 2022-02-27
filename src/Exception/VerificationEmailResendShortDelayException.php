<?php

namespace App\Exception;

use Exception;
use Throwable;

class VerificationEmailResendShortDelayException extends Exception
{
    private int $remainingDelay;

    public function __construct(int $remainingDelay, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->remainingDelay = $remainingDelay;
    }

    /**
     * Time remaining to wait.
     * @return int
     */
    public function getRemainingDelay(): int
    {
        return $this->remainingDelay;
    }
}