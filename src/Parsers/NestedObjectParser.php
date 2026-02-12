<?php

namespace Specdocular\LaravelRulesToSchema\Parsers;

use Specdocular\JsonSchema\Draft202012\Keywords\Properties\Property;
use Specdocular\JsonSchema\Draft202012\Keywords\Type;
use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;
use Specdocular\LaravelRulesToSchema\Contracts\RuleParser;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\ParseResult;

final class NestedObjectParser implements RuleParser
{
    /** @var callable(string, NestedRuleset): ParseResult */
    private $parseRuleset;

    /** @param callable(string, NestedRuleset): ParseResult $parseRuleset */
    public function withParseRuleset(callable $parseRuleset): static
    {
        $clone = clone $this;
        $clone->parseRuleset = $parseRuleset;

        return $clone;
    }

    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        if (!$nestedRuleset->hasChildren()) {
            return ParseResult::single($schema);
        }

        if (null === $this->parseRuleset) {
            return ParseResult::single($schema);
        }

        if ($nestedRuleset->hasWildcardChild()) {
            $result = ($this->parseRuleset)("{$attribute}.*", $nestedRuleset->children['*']);

            if ($result->isSchema()) {
                $schema = $schema->type(Type::array())->items($result->schema());
            }
        } else {
            $properties = [];
            foreach ($nestedRuleset->children as $propName => $childRuleset) {
                $result = ($this->parseRuleset)($propName, $childRuleset);

                if ($result->isSchema()) {
                    $properties[] = Property::create($propName, $result->schema());
                }
            }

            if ([] !== $properties) {
                $schema = $schema->type(Type::object())->properties(...$properties);
            }
        }

        return ParseResult::single($schema);
    }
}
