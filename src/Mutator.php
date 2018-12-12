<?php

namespace Awobaz\Mutator;

use Awobaz\Mutator\Database\Eloquent\Concerns\HasAttributes;
use Awobaz\Mutator\Exceptions\UnregisteredMutatorException;
use Closure;

trait Mutator
{
    use HasAttributes;

    /**
     * The registered getters.
     *
     * @var array
     */
    protected static $registeredGetters = [];

    /**
     * The registered setters.
     *
     * @var array
     */
    protected static $registeredSetters = [];

    public static function getter($name, Closure $getter = null)
    {
        if(is_null($getter)){
            if(!isset(static::$registeredGetters[$name])){
                throw new UnregisteredMutatorException("The getter '{$name}' is not registered");
            }

            return static::$registeredGetters[$name];
        }

        static::$registeredGetters[$name] = $getter;
    }

    public static function setter($name, Closure $setter = null)
    {
        if(is_null($setter)){
            if(!isset(static::$registeredSetters[$name])){
                throw new UnregisteredMutatorException("The setter '{$name}' is not registered");
            }

            return static::$registeredSetters[$name];
        }

        static::$registeredSetters[$name] = $setter;
    }
}
