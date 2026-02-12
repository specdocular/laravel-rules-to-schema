<?php

namespace Specdocular\LaravelRulesToSchema\Parsers;

use Specdocular\JsonSchema\Draft202012\BooleanSchema;
use Specdocular\JsonSchema\Draft202012\Keywords\Properties\Property;
use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;
use Specdocular\JsonSchema\Draft202012\StrictFluentDescriptor;
use Specdocular\LaravelRulesToSchema\Concerns\TracksParserContext;
use Specdocular\LaravelRulesToSchema\Contracts\ContextAwareRuleParser;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\ParseResult;

final class ConditionalExcludeParser implements ContextAwareRuleParser
{
    use TracksParserContext;

    private const VALUE_CONDITION_RULES = [
        'exclude_if' => 'then',
        'exclude_unless' => 'else',
        'missing_if' => 'then',
        'missing_unless' => 'else',
    ];

    private const PRESENCE_CONDITION_RULES = [
        'exclude_with' => 'then',
        'exclude_without' => 'then',
        'missing_with' => 'then',
        'missing_with_all' => 'then',
    ];

    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        if (null === $this->baseSchema || null === $this->allRules) {
            return ParseResult::single($schema);
        }

        foreach ($validationRules as $validationRule) {
            if (!$validationRule->isString()) {
                continue;
            }

            if (array_key_exists($validationRule->rule, self::VALUE_CONDITION_RULES)) {
                $schema = $this->applyValueCondition($schema, $attribute, $validationRule->rule, $validationRule->args);
            } elseif (array_key_exists($validationRule->rule, self::PRESENCE_CONDITION_RULES)) {
                $schema = $this->applyPresenceCondition($schema, $attribute, $validationRule->rule, $validationRule->args);
            }
        }

        return ParseResult::single($schema);
    }

    private function applyValueCondition(LooseFluentDescriptor $schema, string $attribute, string $rule, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()
            ->properties(Property::create($args[0], StrictFluentDescriptor::constant($args[1] ?? null)));

        $excludeSchema = LooseFluentDescriptor::withoutSchema()->not(BooleanSchema::true());

        if ('then' === self::VALUE_CONDITION_RULES[$rule]) {
            return $schema->if($ifSchema)->then($excludeSchema);
        }

        return $schema->if($ifSchema)->else($excludeSchema);
    }

    private function applyPresenceCondition(LooseFluentDescriptor $schema, string $attribute, string $rule, array $args): LooseFluentDescriptor
    {
        $excludeSchema = LooseFluentDescriptor::withoutSchema()->not(BooleanSchema::true());

        if ('exclude_without' === $rule) {
            return $schema
                ->if(LooseFluentDescriptor::withoutSchema()->not(LooseFluentDescriptor::withoutSchema()->required(...$args)))
                ->then($excludeSchema);
        }

        return $schema
            ->if(LooseFluentDescriptor::withoutSchema()->required(...$args))
            ->then($excludeSchema);
    }
}
