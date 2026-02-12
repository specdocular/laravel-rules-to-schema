<?php

namespace Specdocular\LaravelRulesToSchema\Parsers;

use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;
use Specdocular\LaravelRulesToSchema\Contracts\HasDocs;
use Specdocular\LaravelRulesToSchema\Contracts\RuleParser;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\ParseResult;

final readonly class CustomRuleDocsParser implements RuleParser
{
    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        foreach ($validationRules as $validationRule) {
            if (!$validationRule->rule instanceof HasDocs) {
                continue;
            }

            $docs = $validationRule->rule->docs();

            if ($docs->hasType()) {
                $schema = $schema->type($docs->type);
            }

            if ($docs->hasFormat()) {
                $schema = $schema->format($docs->format);
            }

            if ($docs->hasDescription()) {
                $schema = $schema->description($docs->description);
            }

            if ($docs->hasEnum()) {
                $schema = $schema->enum(...$docs->enum);
            }
        }

        return ParseResult::single($schema);
    }
}
