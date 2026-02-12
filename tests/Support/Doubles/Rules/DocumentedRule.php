<?php

namespace Tests\Support\Doubles\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Specdocular\LaravelRulesToSchema\Contracts\HasDocs;
use Specdocular\LaravelRulesToSchema\RuleDocumentation;

class DocumentedRule implements ValidationRule, HasDocs
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
    }

    public function docs(): RuleDocumentation
    {
        return new RuleDocumentation(
            type: 'string',
            format: 'date-time',
            description: 'A valid datetime string',
        );
    }
}
