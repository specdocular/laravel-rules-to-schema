<?php

namespace Specdocular\LaravelRulesToSchema\Parsers;

use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;
use Specdocular\LaravelRulesToSchema\Contracts\RuleParser;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\ParseResult;

/**
 * Required tracking is handled by the orchestrator (RuleToSchema).
 * This parser exists only to filter out fields with `sometimes` rule.
 */
final readonly class RequiredParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        foreach ($validationRules as $validationRule) {
            if (!$validationRule->isString()) {
                continue;
            }

            if ('sometimes' === $validationRule->rule) {
                return ParseResult::single($schema);
            }
        }

        return ParseResult::single($schema);
    }
}
