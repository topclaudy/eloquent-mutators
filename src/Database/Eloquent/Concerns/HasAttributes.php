<?php

namespace Awobaz\Mutator\Database\Eloquent\Concerns;

use Awobaz\Mutator\Facades\Mutator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;

trait HasAttributes
{
    /**
     * Extract and cache all the mutated attributes of a class.
     *
     * @param string $class
     *
     * @return void
     */
    public static function cacheMutatedAttributes($classOrInstance)
    {
        parent::cacheMutatedAttributes($classOrInstance);

        $reflection = new ReflectionClass($classOrInstance);

        $class = $reflection->getName();

        if (property_exists($class, config('mutators.accessors_property'))) {
            static::$mutatorCache[$class] = array_merge(static::$mutatorCache[$class], array_keys(with(new $class())->{config('mutators.accessors_property')}));
        }
    }

    /**
     * Get a plain attribute (not a relationship).
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAttributeValue($key)
    {
        $value = parent::getAttributeValue($key);

        return $this->applyAccessors($key, $value);
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    protected function applyAccessors($key, $value)
    {
        // If the attribute has custom accessors, we will call them
        foreach ($this->getMutatorsFor($key, config('mutators.accessors_property')) as $accessor => $params) {
            $value = Mutator::get($accessor)($this, $value, $key, ...$params);
        }

        return $value;
    }

    /**
     * Set a given attribute on the model.
     *
     * @param string $key
     * @param mixed $value
     *
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
     *
     * @return
     */
    protected function applyMutators($key, $value)
    {
        // If the attribute has custom mutators, we will call them
        foreach ($this->getMutatorsFor($key, config('mutators.mutators_property')) as $mutator => $params) {
            $value = Mutator::get($mutator)($this, $value, $key, ...$params);
        }

        return $value;
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return mixed
     */
    protected function mutateAttribute($key, $value)
    {
        if (! array_key_exists($key, $this->{config('mutators.accessors_property')} ?: [])) {
            $value = parent::mutateAttribute($key, $value);
        } elseif (method_exists($this, 'get'.Str::studly($key).'Attribute')) {
            $value = parent::mutateAttribute($key, $value);
        }

        return $this->applyAccessors($key, $value);
    }

    /**
     * Get the mutators for a given attribute.
     *
     * @param string $key The name of the attribute we want to mutate
     * @param string $type The type of mutation: accessor or mutator
     *
     * @return array
     */
    protected function getMutatorsFor($key, $type)
    {
        $mutators = $this->{$type};
        if (empty($mutators) || ! is_array($mutators) || ! isset($mutators[$key])) {
            return [];
        }

        $result = [];
        foreach ((array) $mutators[$key] as $mutator => $params) {
            $parsed = $this->parseMutatorNameAndParams($mutator, $params);
            $result[$parsed[0]] = $parsed[1];
        }

        return $result;
    }

    /**
     * Separates the mutator name from its optional parameters.
     *
     * @param int|string $mutator
     * @param string|array|mixed $params
     *
     * @return array
     */
    protected function parseMutatorNameAndParams($mutator, $params)
    {
        if (is_int($mutator) && is_string($params)) {
            $params = explode(':', $params);
            $mutator = array_shift($params);

            return [$mutator, count($params) > 1 ? str_getcsv(implode(':', $params)) : []];
        }

        return [$mutator, Arr::wrap($params)];
    }
}
