<?php

namespace Awobaz\Mutator\Database\Eloquent;

use Awobaz\Mutator\Mutable;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
    use Mutable;
}
