<?php

namespace Specdocular\LaravelRulesToSchema\Parsers;

use Specdocular\LaravelRulesToSchema\Contracts\RuleParser;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\ParseResult;
use Specdocular\JsonSchema\Draft202012\Formats\StringFormat;
use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;

final readonly class AdditionalConstraintParser implements RuleParser
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

            $schema = match ($validationRule->rule) {
                'active_url' => $schema->format(StringFormat::URI),
                'timezone' => $schema->format('timezone'),
                'filled' => $this->applyFilled($schema),
                'distinct' => $schema->uniqueItems(true),
                'extensions' => $schema->enum(...$validationRule->args),
                default => $schema,
            };
        }

        return ParseResult::single($schema);
    }

    private function applyFilled(LooseFluentDescriptor $schema): LooseFluentDescriptor
    {
        $schemaType = $schema->getType();
        $types = is_array($schemaType) ? $schemaType : ($schemaType ? [$schemaType] : []);

        foreach ($types as $type) {
            if ('array' === $type) {
                return $schema->minItems(1);
            }
        }

        return $schema->minLength(1);
    }
}
