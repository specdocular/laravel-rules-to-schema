<?php

namespace Specdocular\LaravelRulesToSchema\Parsers;

use Specdocular\LaravelRulesToSchema\Contracts\HasJsonSchema;
use Specdocular\LaravelRulesToSchema\Contracts\RuleParser;
use Specdocular\LaravelRulesToSchema\CustomRuleSchemaMapping;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\ParseResult;
use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;

final readonly class CustomRuleSchemaParser implements RuleParser
{
    /** @param array<string, CustomRuleSchemaMapping> $customRuleSchemas */
    public function __construct(
        private array $customRuleSchemas = [],
    ) {
    }

    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        foreach ($validationRules as $validationRule) {
            if ($validationRule->rule instanceof HasJsonSchema) {
                return ParseResult::single($validationRule->rule->toJsonSchema($attribute));
            }

            $ruleName = $validationRule->name();

            if (array_key_exists($ruleName, $this->customRuleSchemas)) {
                return ParseResult::single($this->customRuleSchemas[$ruleName]->apply($attribute, $schema));
            }
        }

        return ParseResult::single($schema);
    }
}
