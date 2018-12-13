<?php

namespace Awobaz\Mutator\Database\Eloquent\Concerns;

use Awobaz\Mutator\Facades\Mutator;

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
        // If the attribute has custom accessors, we will call them
        if(property_exists($this, config('mutators.accessors_property')) && isset($this->{config('mutators.accessors_property')}[$key])) {
            $value = $this->getAttributeFromArray($key);

            $accessors = array_wrap($this->{config('mutators.accessors_property')}[$key]);

            foreach($accessors as $accessor){
                $value = Mutator::get($accessor)($this, $value, $key);
            }

            return $value;
        }

        return parent::getAttributeValue($key);
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
        // If the attribute has custom mutators, we will call them
        if(property_exists($this, config('mutators.mutators_property')) && isset($this->{config('mutators.mutators_property')}[$key])) {

            $mutators = array_wrap($this->{config('mutators.mutators_property')}[$key]);

            foreach($mutators as $mutator){
                $this->attributes[$key] = Mutator::get($mutator)($this, $value, $key);
            }

            return $this->attributes[$key];
        }

        return parent::setAttribute($key, $value);
    }
}
