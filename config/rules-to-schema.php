<?php

use Specdocular\LaravelRulesToSchema\Parsers\AcceptedDeclinedParser;
use Specdocular\LaravelRulesToSchema\Parsers\AdditionalConstraintParser;
use Specdocular\LaravelRulesToSchema\Parsers\ComparisonConstraintParser;
use Specdocular\LaravelRulesToSchema\Parsers\ConditionalAcceptedParser;
use Specdocular\LaravelRulesToSchema\Parsers\ConditionalExcludeParser;
use Specdocular\LaravelRulesToSchema\Parsers\ConditionalProhibitedParser;
use Specdocular\LaravelRulesToSchema\Parsers\ConditionalRequiredParser;
use Specdocular\LaravelRulesToSchema\Parsers\ConfirmedParser;
use Specdocular\LaravelRulesToSchema\Parsers\CustomRuleDocsParser;
use Specdocular\LaravelRulesToSchema\Parsers\CustomRuleSchemaParser;
use Specdocular\LaravelRulesToSchema\Parsers\EnumParser;
use Specdocular\LaravelRulesToSchema\Parsers\ExampleOverride;
use Specdocular\LaravelRulesToSchema\Parsers\ExcludedParser;
use Specdocular\LaravelRulesToSchema\Parsers\FileUploadParser;
use Specdocular\LaravelRulesToSchema\Parsers\FormatParser;
use Specdocular\LaravelRulesToSchema\Parsers\MiscPropertyParser;
use Specdocular\LaravelRulesToSchema\Parsers\NestedObjectParser;
use Specdocular\LaravelRulesToSchema\Parsers\NotInParser;
use Specdocular\LaravelRulesToSchema\Parsers\NumericConstraintParser;
use Specdocular\LaravelRulesToSchema\Parsers\PasswordParser;
use Specdocular\LaravelRulesToSchema\Parsers\PresentFieldParser;
use Specdocular\LaravelRulesToSchema\Parsers\RequiredParser;
use Specdocular\LaravelRulesToSchema\Parsers\RequiredWithoutParser;
use Specdocular\LaravelRulesToSchema\Parsers\RequiredWithParser;
use Specdocular\LaravelRulesToSchema\Parsers\StringPatternParser;
use Specdocular\LaravelRulesToSchema\Parsers\TypeParser;

return [
    /*
     * The parsers to run rules through
     */
    'parsers' => [
        TypeParser::class,
        NestedObjectParser::class,
        RequiredParser::class,
        MiscPropertyParser::class,
        FormatParser::class,
        EnumParser::class,
        ExcludedParser::class,
        ConfirmedParser::class,
        CustomRuleSchemaParser::class,
        CustomRuleDocsParser::class,
        FileUploadParser::class,
        PasswordParser::class,
        StringPatternParser::class,
        ComparisonConstraintParser::class,
        NumericConstraintParser::class,
        NotInParser::class,
        AcceptedDeclinedParser::class,
        AdditionalConstraintParser::class,
        ExampleOverride::class,
        RequiredWithParser::class,
        RequiredWithoutParser::class,
        ConditionalRequiredParser::class,
        ConditionalExcludeParser::class,
        ConditionalProhibitedParser::class,
        PresentFieldParser::class,
        ConditionalAcceptedParser::class,
    ],

    /*
     * Third party rules that you can provide custom schema definitions for
     */
    'custom_rule_schemas' => [
        // \CustomPackage\CustomRule::class => \Support\CustomRuleSchemaDefinition::class,
        // \CustomPackage\CustomRule::class => 'string',
        // \CustomPackage\CustomRule::class => ['null', 'string'],
    ],
];
