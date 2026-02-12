<?php

namespace Specdocular\LaravelRulesToSchema\Contracts;

use Specdocular\JsonSchema\Draft202012\LooseFluentDescriptor;

interface HasJsonSchema
{
    public function toJsonSchema(string $attribute): LooseFluentDescriptor;
}
