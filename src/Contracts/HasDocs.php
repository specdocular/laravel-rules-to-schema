<?php

namespace Specdocular\LaravelRulesToSchema\Contracts;

use Specdocular\LaravelRulesToSchema\RuleDocumentation;

interface HasDocs
{
    public function docs(): RuleDocumentation;
}
