<?php

namespace Specdocular\LaravelRulesToSchema\Parsers;

use Illuminate\Validation\Rules\Enum as EnumRule;
use Specdocular\LaravelRulesToSchema\Contracts\RuleParser;
use Specdocular\LaravelRulesToSchema\LaravelRuleInternals;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\ParseResult;
use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;
use ReflectionClass;

final readonly class EnumParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        foreach ($validationRules as $validationRule) {
            if ($validationRule->rule instanceof EnumRule) {
                $enumType = LaravelRuleInternals::enumType($validationRule->rule);
                $reflection = new ReflectionClass($enumType);

                if (count($reflection->getConstants()) > 0) {
                    $values = array_values(array_map(
                        static fn (\UnitEnum|\BackedEnum $c): string|int => $c instanceof \BackedEnum ? $c->value : $c->name,
                        $reflection->getConstants(),
                    ));

                    $schema = $schema->enum(...$values);
                }
            }
        }

        return ParseResult::single($schema);
    }
}
