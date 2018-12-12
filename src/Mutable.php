<?php

namespace Awobaz\Mutator;

use Awobaz\Mutator\Database\Eloquent\Concerns\HasAttributes;
use Awobaz\Mutator\Exceptions\UnregisteredMutatorException;
use Closure;

trait Mutable
{
    use HasAttributes;
}
