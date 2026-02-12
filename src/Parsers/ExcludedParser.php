<?php

namespace Specdocular\LaravelRulesToSchema\Parsers;

use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;
use Specdocular\LaravelRulesToSchema\Contracts\RuleParser;
use Specdocular\LaravelRulesToSchema\LaravelRuleType;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\ParseResult;

final readonly class ExcludedParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        foreach ($validationRules as $validationRule) {
            if ($validationRule->isString() && in_array($validationRule->rule, LaravelRuleType::exclude(), true)) {
                return ParseResult::excluded();
            }
        }

        return ParseResult::single($schema);
    }
}
