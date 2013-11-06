<?php

namespace MxcGenerics\Stdlib;

use \Traversable;
use MxcGenerics\Exception;

class GenericRegistry implements \IteratorAggregate {

    protected $___data = array();
    
    /**
     * Constructor
     *
     * @param  array|Traversable|null $options
     */
    public function __construct($options = null)
    {
        if ($options) $this->setProperties($options);
    }
    
    /**
     * Magic getter/setter
     *
     */
    public function __call($f, $p) {
        $gs = substr($f,0,3);
        $i = lcfirst(substr($f,-strlen($f)+3));
        if ($gs === 'get') {
            return isset($this->___data[$i]) ? $this->___data[$i] : null;
        } elseif ($gs === 'set') {
            $this->___data[$i] = $p[0];
        }
    }
    
    /**
     * Set a configuration property
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->___data[lcfirst($key)] = $value;
    }

    /**
     * Set a configuration property
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __get($key)
    {
        $key = lcfirst($key);
        return isset($this->___data[$key]) ? $this->___data[$key] : null;
    }
    
    /**
     * Test if a configuration property is null
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return null !== $this->___data[lcfirst($key)];
    }

    /**
     * Set a configuration property to NULL
     *
     * @param string $key
     * @return void
     */
    public function __unset($key)
    {
        $key = lcfirst($key);
        if (isset($this->___data[$key])) unset($this->___data[$key]);
    }

    /**
     * Cast to array
     *
     * @return array
     */
    public function toArray()
    {
        $array = array();
        $transform = function ($letters) {
            $letter = array_shift($letters); 
            return '_' . strtolower($letter);
        };
        foreach ($this->___data as $key => $value) {
            if ($key === '__strictMode__') continue;
            $normalizedKey = preg_replace_callback('/([A-Z])/', $transform, $key);
            $array[$normalizedKey] = $value;
        }
        return $array;
    }

    /**
     * Set properties en masse
     *
     * Can be an array or a Traversable object.
     *
     * @param  array|ArrayAccess|Traversable $properties
     * @param  bool $overwrite Whether or not to overwrite the internal container with $properties
     * @throws Exception\InvalidArgumentException
     * @return GenericRegistry
     */
    public function setProperties($properties, $overwrite = false)
    {
        if (!is_array($properties) && !$properties instanceof Traversable) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s: expects an array, or Traversable argument; received "%s"',
                __METHOD__,
                (is_object($properties) ? get_class($properties) : gettype($properties))
            ));
        }

        if ($overwrite) $this->clear();
        
        if ($properties instanceof self) {
            $this->___data = array_merge($this->___data, $properties->get___Data());
            return $this;
        }

        foreach ($properties as $key => $value) {
            $i = str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));        
            $this->___data[lcfirst($i)] = $value;
        }
        return $this;
    }
    
    
    public function getIterator() {
        return new \ArrayIterator($this->___data);
    }
    
    public function get___Data() {
        return $this->___data;
    }
    
    /**
     * Check all stored registry data.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->___data;
    }
    
    
    public function clear() {
        $this->___data = array();
    }
    
    public function count() {
        return count($this->___data);
    }
}