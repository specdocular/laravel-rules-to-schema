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

final class ConditionalProhibitedParser implements ContextAwareRuleParser
{
    use TracksParserContext;

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

            $schema = match ($validationRule->rule) {
                'prohibited_if' => $this->applyProhibitedIf($schema, $attribute, $validationRule->args),
                'prohibited_unless' => $this->applyProhibitedUnless($schema, $attribute, $validationRule->args),
                'prohibits' => $this->applyProhibits($schema, $attribute, $validationRule->args),
                default => $schema,
            };
        }

        return ParseResult::single($schema);
    }

    private function applyProhibitedIf(LooseFluentDescriptor $schema, string $attribute, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()
            ->properties(Property::create($args[0], StrictFluentDescriptor::constant($args[1] ?? null)));

        return $schema->if($ifSchema)->then(LooseFluentDescriptor::withoutSchema()->not(BooleanSchema::true()));
    }

    private function applyProhibitedUnless(LooseFluentDescriptor $schema, string $attribute, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()
            ->properties(Property::create($args[0], StrictFluentDescriptor::constant($args[1] ?? null)));

        return $schema->if($ifSchema)->else(LooseFluentDescriptor::withoutSchema()->not(BooleanSchema::true()));
    }

    private function applyProhibits(LooseFluentDescriptor $schema, string $attribute, array $args): LooseFluentDescriptor
    {
        return $schema
            ->if(LooseFluentDescriptor::withoutSchema()->required($attribute))
            ->then(LooseFluentDescriptor::withoutSchema()->not(BooleanSchema::true()));
    }
}
