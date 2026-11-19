<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Rejects text with common spam signs: words spammers use and long runs of the
 * same letter or symbol. Digits and spaces may repeat, so "10000000" passes.
 */
class NoSpamContent implements ValidationRule
{
    /** Whole words only, so "cryptographer" does not match "crypto". */
    private const BLOCKED_WORDS = ['crypto', 'bitcoin', 'casino', 'viagra', 'forex'];

    /** The same non-digit, non-space character seven or more times in a row. */
    private const REPEATED_CHARACTER_PATTERN = '/([^\d\s])\1{6,}/u';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        if ($this->containsBlockedWord($value) || preg_match(self::REPEATED_CHARACTER_PATTERN, $value) === 1) {
            $fail('Your :attribute looks like spam. Please describe your project.');
        }
    }

    private function containsBlockedWord(string $text): bool
    {
        $blockedWordPattern = '/\b('.implode('|', self::BLOCKED_WORDS).')\b/iu';

        return preg_match($blockedWordPattern, $text) === 1;
    }
}
