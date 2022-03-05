<?php

namespace App\Form\Constraint;

use Symfony\Component\Validator\Constraints\Regex;

/**
 * Validation for a phone number. It is the same as Regex validation using given regular expression.
 * @Annotation
 */
class PhoneNumber extends Regex
{
    public $message = 'invalid_phone_number_format';

    public function __construct(array $options = [], string $message = null, string $htmlPattern = null, bool $match = null, callable $normalizer = null, array $groups = null, mixed $payload = null)
    {
        /** Regex: https://php.baraja.cz/regex */
        $regex = "/^(\+420)?\s?(\d{3}\s?){3}$/";
        parent::__construct($regex, $message, $htmlPattern, $match, $normalizer, $groups, $payload, $options);
    }
}