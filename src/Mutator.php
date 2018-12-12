<?php

namespace Awobaz\Mutator;

use Awobaz\Mutator\Database\Eloquent\Concerns\HasAttributes;
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
            //TODO: check definition and throw exception
            return static::$registeredGetters[$name];
        }

        static::$registeredGetters[$name] = $getter;
    }

    public static function setter($name, Closure $setter = null)
    {
        if(is_null($setter)){
            //TODO: check definition and throw exception
            return static::$registeredSetters[$name];
        }

        static::$registeredSetters[$name] = $setter;
    }
}
