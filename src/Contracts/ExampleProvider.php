<?php

namespace Specdocular\LaravelRulesToSchema\Contracts;

interface ExampleProvider
{
    public function has(string $rule): bool;

    /** @return list<mixed> */
    public function get(string $rule): array;
}
