<?php

namespace Awobaz\Mutator\Database\Eloquent\Concerns;

use Awobaz\Mutator\Facades\Mutator;
use Illuminate\Support\Str;

trait HasAttributes
{
    /**
     * Get a plain attribute (not a relationship).
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttributeValue($key)
    {
        $value = parent::getAttributeValue($key);

        return $this->applyAccessors($key, $value);
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function setAttribute($key, $value)
    {
        $value = $this->applyMutators($key, $value);

        return parent::setAttribute($key, $value);
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function applyAccessors($key, $value)
    {
        // If the attribute has custom accessors, we will call them
        if (property_exists($this, config('mutators.accessors_property')) && isset($this->{config('mutators.accessors_property')}[ $key ])) {
            $accessors = array_wrap($this->{config('mutators.accessors_property')}[ $key ]);

            foreach ($accessors as $accessor) {
                $value = Mutator::get($accessor)($this, $value, $key);
            }
        }

        return $value;
    }

    /**
     * @param $key
     * @param $value
     * @return
     */
    protected function applyMutators($key, $value)
    {
        // If the attribute has custom mutators, we will call them
        if (property_exists($this, config('mutators.mutators_property')) && isset($this->{config('mutators.mutators_property')}[ $key ])) {

            $mutators = array_wrap($this->{config('mutators.mutators_property')}[ $key ]);

            foreach ($mutators as $mutator) {
                $this->attributes[ $key ] = Mutator::get($mutator)($this, $value, $key);
            }

            return $this->attributes[ $key ];
        }

        return $value;
    }

    /**
     * Extract and cache all the mutated attributes of a class.
     *
     * @param  string  $class
     * @return void
     */
    public static function cacheMutatedAttributes($class)
    {
        parent::cacheMutatedAttributes($class);

        if (property_exists($class, config('mutators.accessors_property'))) {
            static::$mutatorCache[$class] = array_merge(static::$mutatorCache[$class], array_keys(with(new $class)->{config('mutators.accessors_property')}));
        }
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function mutateAttribute($key, $value)
    {
        $value = parent::mutateAttribute($key, $value);
        return $this->applyAccessors($key, $value);
    }
}
