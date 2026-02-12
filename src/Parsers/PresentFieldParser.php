<?php

namespace Specdocular\LaravelRulesToSchema\Parsers;

use Specdocular\JsonSchema\Draft202012\Keywords\Properties\Property;
use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;
use Specdocular\JsonSchema\Draft202012\StrictFluentDescriptor;
use Specdocular\LaravelRulesToSchema\Concerns\TracksParserContext;
use Specdocular\LaravelRulesToSchema\Contracts\ContextAwareRuleParser;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\ParseResult;

final class PresentFieldParser implements ContextAwareRuleParser
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
                'present' => $schema->required($attribute),
                'present_if' => $this->applyPresentIf($schema, $attribute, $validationRule->args),
                'present_unless' => $this->applyPresentUnless($schema, $attribute, $validationRule->args),
                'present_with' => $this->applyPresentWith($schema, $attribute, $validationRule->args),
                'present_with_all' => $this->applyPresentWithAll($schema, $attribute, $validationRule->args),
                default => $schema,
            };
        }

        return ParseResult::single($schema);
    }

    private function applyPresentIf(LooseFluentDescriptor $schema, string $attribute, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()
            ->properties(Property::create($args[0], StrictFluentDescriptor::constant($args[1] ?? null)));

        $thenSchema = LooseFluentDescriptor::withoutSchema()->required($attribute);

        return $schema->if($ifSchema)->then($thenSchema);
    }

    private function applyPresentUnless(LooseFluentDescriptor $schema, string $attribute, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()
            ->properties(Property::create($args[0], StrictFluentDescriptor::constant($args[1] ?? null)));

        $elseSchema = LooseFluentDescriptor::withoutSchema()->required($attribute);

        return $schema->if($ifSchema)->else($elseSchema);
    }

    private function applyPresentWith(LooseFluentDescriptor $schema, string $attribute, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()->required(...$args);
        $thenSchema = LooseFluentDescriptor::withoutSchema()->required($attribute);

        return $schema->if($ifSchema)->then($thenSchema);
    }

    private function applyPresentWithAll(LooseFluentDescriptor $schema, string $attribute, array $args): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()->required(...$args);
        $thenSchema = LooseFluentDescriptor::withoutSchema()->required($attribute);

        return $schema->if($ifSchema)->then($thenSchema);
    }
}
