<?php

namespace Awobaz\Mutator\Database\Eloquent;

use Awobaz\Mutator\Mutator;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
    use Mutator;
}