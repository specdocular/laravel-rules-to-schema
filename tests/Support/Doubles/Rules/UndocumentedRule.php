<?php

namespace Tests\Support\Doubles\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class UndocumentedRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
    }
}
