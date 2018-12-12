<?php

namespace Awobaz\Mutator;

use Awobaz\Mutator\Database\Eloquent\Concerns\HasAttributes;
use Awobaz\Mutator\Exceptions\UnregisteredMutatorException;
use Closure;

class Mutator
{
    /**
     * The registered mutators.
     *
     * @var array
     */
    protected static $registeredMutators = [];

    public static function add($name, Closure $getter)
    {
        static::$registeredMutators[$name] = $getter;

        return new static;
    }

    public static function get($name)
    {
        if(!isset(static::$registeredMutators[$name])){
            throw new UnregisteredMutatorException("The mutator '{$name}' is not registered");
        }

        return static::$registeredMutators[$name];
    }
}
