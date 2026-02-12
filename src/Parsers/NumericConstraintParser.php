<?php

namespace Specdocular\LaravelRulesToSchema\Parsers;

use Specdocular\LaravelRulesToSchema\Contracts\RuleParser;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\ParseResult;
use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;

final readonly class NumericConstraintParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        foreach ($validationRules as $validationRule) {
            if (!$validationRule->isString() || [] === $validationRule->args) {
                continue;
            }

            $value = (int) $validationRule->args[0];

            $schema = match ($validationRule->rule) {
                'multiple_of' => $schema->multipleOf($value),
                'max_digits' => $schema->maximum((int) (10 ** $value - 1)),
                'min_digits' => $schema->minimum($value <= 1 ? 0 : (int) (10 ** ($value - 1))),
                default => $schema,
            };
        }

        return ParseResult::single($schema);
    }
}
