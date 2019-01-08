<?php

namespace Awobaz\Mutator\Facades;

/*
 * This file is part of Mutator,
 * eloquent getters/setters solution for Laravel 5's Eloquent.
 *
 * @license MIT
 * @package Awobaz\Mutators
 */

use Illuminate\Support\Facades\Facade;

class Mutator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mutator';
    }
}
