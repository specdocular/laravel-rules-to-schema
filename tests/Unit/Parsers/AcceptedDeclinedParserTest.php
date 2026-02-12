<?php

use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\Parsers\AcceptedDeclinedParser;
use Specdocular\LaravelRulesToSchema\ValidationRule;

describe(class_basename(AcceptedDeclinedParser::class), function (): void {
    it('sets boolean type for accepted rule', function (): void {
        $parser = new AcceptedDeclinedParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('terms', $schema, [new ValidationRule('accepted')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['type'])->toBe('boolean');
    });

    it('sets boolean type for declined rule', function (): void {
        $parser = new AcceptedDeclinedParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('opt_out', $schema, [new ValidationRule('declined')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled['type'])->toBe('boolean');
    });

    it('does not modify schema for non-accepted/declined rules', function (): void {
        $parser = new AcceptedDeclinedParser();
        $schema = LooseFluentDescriptor::withoutSchema();

        $result = $parser('field', $schema, [new ValidationRule('required'), new ValidationRule('string')], new NestedRuleset());

        $compiled = $result->schema()->compile();

        expect($compiled)->not->toHaveKey('type');
    });
})->covers(AcceptedDeclinedParser::class);
