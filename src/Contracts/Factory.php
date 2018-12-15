<?php

namespace Awobaz\Mutator\Contracts;

use Closure;

interface Factory
{
    /**
     * Register a custom mutator extension.
     *
     * @param  string $mutator
     * @param  \Closure|string $extension
     * @return void
     */
    public function extend($mutator, Closure $extension);
}
