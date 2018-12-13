<?php

namespace Awobaz\Mutator;

use Awobaz\Mutator\Contracts\Factory;
use Awobaz\Mutator\Exceptions\UnregisteredMutatorException;
use Closure;

class Mutator implements Factory
{
    /**
     * The registered mutators.
     *
     * @var array
     */
    private $extensions = [];

    /**
     * Register a custom mutator extension.
     *
     * @param  string $mutator
     * @param  \Closure|string $extension
     * @return Mutator
     */
    public function extend($mutator, Closure $extension)
    {
        $this->extensions[$mutator] = $extension;

        return $this;
    }

    public function get($name)
    {
        if(!isset($this->extensions[$name])){
            throw new UnregisteredMutatorException("The mutator '{$name}' is not registered");
        }

        return $this->extensions[$name];
    }
}
