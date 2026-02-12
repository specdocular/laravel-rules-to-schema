<?php

namespace Specdocular\LaravelRulesToSchema\Parsers;

use Specdocular\LaravelRulesToSchema\Concerns\TracksParserContext;
use Specdocular\LaravelRulesToSchema\Contracts\ContextAwareRuleParser;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\ParseResult;
use Specdocular\JsonSchema\Draft202012\Keywords\Properties\Property;
use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;
use Specdocular\JsonSchema\Draft202012\StrictFluentDescriptor;

final class ConditionalAcceptedParser implements ContextAwareRuleParser
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
                'accepted_if' => $this->applyConditional($schema, $validationRule->args, true),
                'declined_if' => $this->applyConditional($schema, $validationRule->args, false),
                default => $schema,
            };
        }

        return ParseResult::single($schema);
    }

    private function applyConditional(LooseFluentDescriptor $schema, array $args, bool $acceptedValue): LooseFluentDescriptor
    {
        $ifSchema = LooseFluentDescriptor::withoutSchema()
            ->properties(Property::create($args[0], StrictFluentDescriptor::constant($args[1] ?? null)));

        $thenSchema = StrictFluentDescriptor::boolean()->const($acceptedValue);

        return $schema->if($ifSchema)->then($thenSchema);
    }
}
