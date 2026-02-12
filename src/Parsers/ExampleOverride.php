<?php

namespace Specdocular\LaravelRulesToSchema\Parsers;

use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;
use Specdocular\LaravelRulesToSchema\Concerns\TracksParserContext;
use Specdocular\LaravelRulesToSchema\Contracts\ContextAwareRuleParser;
use Specdocular\LaravelRulesToSchema\Contracts\ExampleProvider;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\ParseResult;

final class ExampleOverride implements ContextAwareRuleParser
{
    use TracksParserContext;

    public function __construct(
        private readonly ExampleProvider|null $exampleProvider = null,
    ) {
    }

    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        if (null === $this->baseSchema || null === $this->allRules || null === $this->exampleProvider) {
            return ParseResult::single($schema);
        }

        foreach ($validationRules as $validationRule) {
            $ruleName = $validationRule->name();

            if ($this->exampleProvider->has($ruleName)) {
                $example = $this->exampleProvider->get($ruleName);
                $currentExamples = $schema->getExamples() ?? [];
                $schema = $schema->examples(...[...$currentExamples, ...$example]);
            }
        }

        return ParseResult::single($schema);
    }
}
