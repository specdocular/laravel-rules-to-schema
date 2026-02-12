<?php

namespace Specdocular\LaravelRulesToSchema\Parsers;

use Specdocular\JsonSchema\Draft202012\Keywords\Type;
use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;
use Specdocular\LaravelRulesToSchema\Contracts\RuleParser;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\ParseResult;

final readonly class AcceptedDeclinedParser implements RuleParser
{
    private const RULES = ['accepted', 'declined'];

    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        foreach ($validationRules as $validationRule) {
            if ($validationRule->isString() && in_array($validationRule->rule, self::RULES, true)) {
                return ParseResult::single($schema->type(Type::boolean()));
            }
        }

        return ParseResult::single($schema);
    }
}
