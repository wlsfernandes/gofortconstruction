<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Accepts text written in the Latin alphabet (English, Spanish, Portuguese and
 * their accents), plus digits, punctuation, symbols and emoji. Rejects text that
 * uses other alphabets, such as Cyrillic or Chinese, which on this site is spam.
 */
class LatinScriptOnly implements ValidationRule
{
    /** Any character outside the Latin script and the script-neutral sets. */
    private const NON_LATIN_CHARACTER_PATTERN = '/[^\p{Latin}\p{Common}\p{Inherited}]/u';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        if (preg_match(self::NON_LATIN_CHARACTER_PATTERN, $value) === 1) {
            $fail('Please write your :attribute in English, Spanish, or Portuguese.');
        }
    }
}
