<?php

namespace Specdocular\LaravelRulesToSchema\Parsers;

use Specdocular\JsonSchema\Draft202012\Keywords\Type;
use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;
use Specdocular\LaravelRulesToSchema\Contracts\RuleParser;
use Specdocular\LaravelRulesToSchema\NestedRuleset;
use Specdocular\LaravelRulesToSchema\ParseResult;

final readonly class FileUploadParser implements RuleParser
{
    private const FILE_RULES = ['file', 'image', 'mimes', 'mimetypes'];

    public function __invoke(
        string $attribute,
        LooseFluentDescriptor $schema,
        array $validationRules,
        NestedRuleset $nestedRuleset,
    ): ParseResult {
        foreach ($validationRules as $validationRule) {
            if ($validationRule->isString() && in_array($validationRule->rule, self::FILE_RULES, true)) {
                return ParseResult::single($schema->type(Type::string())->format('binary'));
            }
        }

        return ParseResult::single($schema);
    }
}
