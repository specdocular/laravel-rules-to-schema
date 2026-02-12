<?php

namespace Specdocular\LaravelRulesToSchema\Contracts;

use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\ParseResult;

interface RuleParser
{
    /** @param list<\Specdocular\LaravelRulesToSchema\ValidationRule> $validationRules */
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult;
}
