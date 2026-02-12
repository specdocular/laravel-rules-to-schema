<?php

use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\Parsers\CustomRuleDocsParser;
use Specdocular\LaravelRulesToSchema\ValidationRule;
use Tests\Support\Doubles\Rules\DocumentedRule;
use Tests\Support\Doubles\Rules\EnumDocumentedRule;
use Tests\Support\Doubles\Rules\UndocumentedRule;

describe(class_basename(CustomRuleDocsParser::class), function (): void {
    it('applies type and format from docs() method', function (): void {
        $parser = new CustomRuleDocsParser();
        $schema = LooseFluentDescriptor::withoutSchema();
        $rule = new DocumentedRule();

        $result = $parser('field', $schema, [new ValidationRule($rule)], new NestedRuleset());

        expect($result->schema())->toBeInstanceOf(LooseFluentDescriptor::class);

        $compiled = $result->schema()->compile();

        expect($compiled['type'])->toBe('string')
            ->and($compiled['format'])->toBe('date-time')
            ->and($compiled['description'])->toBe('A valid datetime string');
    });

    it('applies enum values from docs() method', function (): void {
        $parser = new CustomRuleDocsParser();
        $schema = LooseFluentDescriptor::withoutSchema();
        $rule = new EnumDocumentedRule();

        $result = $parser('status', $schema, [new ValidationRule($rule)], new NestedRuleset());

        expect($result->schema())->toBeInstanceOf(LooseFluentDescriptor::class);

        $compiled = $result->schema()->compile();

        expect($compiled['type'])->toBe('string')
            ->and($compiled['enum'])->toBe(['active', 'inactive', 'pending']);
    });

    it('skips rules without docs() method', function (): void {
        $parser = new CustomRuleDocsParser();
        $schema = LooseFluentDescriptor::withoutSchema();
        $rule = new UndocumentedRule();

        $result = $parser('field', $schema, [new ValidationRule($rule)], new NestedRuleset());

        expect($result->schema())->toBeInstanceOf(LooseFluentDescriptor::class);

        $compiled = $result->schema()->compile();

        expect($compiled)->toBe([]);
    });

    it('skips string rules', function (): void {
        $parser = new CustomRuleDocsParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('required')], new NestedRuleset());

        expect($result->schema())->toBeInstanceOf(LooseFluentDescriptor::class);

        $compiled = $result->schema()->compile();

        expect($compiled)->toBe([]);
    });
})->covers(CustomRuleDocsParser::class);
