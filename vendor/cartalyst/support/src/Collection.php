<?php

/**
 * Part of the Support package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Support
 * @version    2.0.1
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011-2017, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Cartalyst\Support;

use Closure;
use Countable;
use ArrayAccess;

class Collection implements ArrayAccess, Countable
{
    /**
     * Holds all the collection attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Holds all the collection items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Constructor.
     *
     * @param  mixed  $id
     * @param  \Closure  $callback
     * @return void
     */
    public function __construct($id, Closure $callback = null)
    {
        $this->id = $id;

        $this->executeCallback($callback);
    }

    /**
     * Returns all of the items in the collection.
     *
     * @return array
     */
    public function all()
    {
        $this->executeBeforeCallback();

        return $this->items;
    }

    /**
     * Attaches the given collection into the current collection.
     *
     * @param  \Cartalyst\Support\Collection  $collection
     * @param  string  $type
     * @return $this
     */
    public function attach(Collection $collection, $type = 'Cartalyst\Support\Collection')
    {
        if ( ! is_null($type) && $collection instanceof $type) {
            if ( ! $this->find($collection->id)) {
                $this->put($collection->id, $collection);
            }
        }

        return $this;
    }

    /**
     * Counts the number of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        $this->executeBeforeCallback();

        return count($this->items);
    }

    /**
     * Executes the given callback.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function executeCallback(Closure $callback = null)
    {
        if ($callback instanceof Closure) {
            call_user_func($callback, $this);
        }
    }

    /**
     * Determines if the given item exists in the collection.
     *
     * @param  string  $key
     * @return bool
     */
    public function exists($key)
    {
        return (bool) $this->offsetExists($key);
    }

    /**
     * Returns the given item from the collection.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function find($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * Returns the first item from the collection.
     *
     * @return mixed|null
     */
    public function first()
    {
        $this->executeBeforeCallback();

        return count($this->items) > 0 ? reset($this->items) : null;
    }

    /**
     * Get an attribute from the collection.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return $default;
    }

    /**
     * Get the attributes from the collection.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Determines if an item exists in the collection by key.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function has($key)
    {
        $this->executeBeforeCallback();

        return array_key_exists($key, $this->items);
    }

    /**
     * Determines if the collection is empty or not.
     *
     * @return bool
     */
    public function isEmpty()
    {
        $this->executeBeforeCallback();

        return empty($this->items);
    }

    /**
     * Returns the last item from the collection.
     *
     * @return mixed|null
     */
    public function last()
    {
        $this->executeBeforeCallback();

        return count($this->items) > 0 ? end($this->items) : null;
    }

    /**
     * Makes the given item to be sorted first on the collection.
     *
     * @param  string  $item
     * @return $this
     */
    public function makeFirst($item)
    {
        if (isset($this->items[$item])) {
            $this->items = [ $item => $this->items[$item] ] + $this->items;
        }

        return $this;
    }

    /**
     * Makes the given item to be sorted last on the collection.
     *
     * @param  string  $item
     * @return $this
     */
    public function makeLast($item)
    {
        if (isset($this->items[$item])) {
            $_item = $this->items[$item];

            unset($this->items[$item]);

            $this->put($item, $_item);
        }

        return $this;
    }

    /**
     * Determines if the given offset exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        $this->executeBeforeCallback();

        if (isset($this->items[$key])) {
            return true;
        }

        return false;
    }

    /**
     * Get the value for a given offset.
     *
     * @param  string  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        $this->executeBeforeCallback();

        if (isset($this->items[$key])) {
            return $this->items[$key];
        }
    }

    /**
     * Set the value at the given offset.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->executeBeforeCallback();

        $this->items[$key] = $value;
    }

    /**
     * Unset the value at the given offset.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->executeBeforeCallback();

        unset($this->items[$key]);
    }

    /**
     * Unset the value at the given offset.
     *
     * @param  string  $key
     * @return void
     */
    public function pull($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * Set the value at the given offset.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function put($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * Sort the collection using the given Closure.
     *
     * @param  \Closure|string  $callback
     * @param  int  $options
     * @param  bool  $descending
     * @return $this
     */
    public function sortBy($callback, $options = SORT_REGULAR, $descending = false)
    {
        $results = [];

        if (is_string($callback)) {
            $callback = function ($item) use ($callback) {
                if (is_null($callback)) {
                    return $item;
                }

                foreach (explode('.', $callback) as $segment) {
                    if (is_array($item)) {
                        if ( ! array_key_exists($segment, $item)) {
                            return null;
                        }

                        $item = $item[$segment];
                    }
                }

                return $item;
            };
        }

        // First we will loop through the items and get the comparator from a callback
        // function which we were given. Then, we will sort the returned values and
        // and grab the corresponding values for the sorted keys from this array.
        foreach ($this->items as $key => $value) {
            $results[$key] = $callback($value);
        }

        $descending ? arsort($results, $options) : asort($results, $options);

        // Once we have sorted all of the keys in the array, we will loop through them
        // and grab the corresponding model so we can set the underlying items list
        // to the sorted version. Then we'll just return the collection instance.
        foreach (array_keys($results) as $key) {
            $results[$key] = $this->items[$key];
        }

        $this->items = $results;

        return $this;
    }

    /**
     * Sort the collection in descending order using the given Closure.
     *
     * @param  \Closure|string  $callback
     * @param  int  $options
     * @return $this
     */
    public function sortByDesc($callback, $options = SORT_REGULAR)
    {
        return $this->sortBy($callback, $options, true);
    }

    /**
     * Returns the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            return $value;
        }, $this->items);
    }

    /**
     * Dynamically retrieve the value of an attribute.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        $value = $this->get($key);

        if (method_exists($this, $method = "{$key}Attribute")) {
            return $this->{$method}($value);
        }

        return $value;
    }

    /**
     * Dynamically set the value of an attribute.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Dynamically check if an attribute is set.
     *
     * @param  string  $key
     * @return void
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Dynamically unset an attribute.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }

    /**
     * Calls the beforeCallback method if it exists on the collection.
     *
     * @return void
     */
    protected function executeBeforeCallback()
    {
        if (method_exists($this, 'beforeCallback')) {
            call_user_func_array([$this, 'beforeCallback'], []);
        }
    }
}
