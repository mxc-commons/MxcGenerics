<?php

namespace MxcGenerics\Stdlib;

use \Traversable;
use Zend\Json\Json;
use Zend\Json\Expr;
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
        $data = isset($this->___data[$key]) ? $this->___data[$key] : null;
        return $data;
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


    public function toArray($recursive = true, $detectJsonExpr = false, $normalize = false)
    {
        return self::_toArray($this->___data, $recursive, $detectJsonExpr, $normalize);
    }
    
    private static function _toArray($iterator, $recursive = true, $detectJsonExpr = false, $normalize = false) 
    {
        
        if (!$recursive && !$normalize && !$detectJsonExpr) return $iterator;
        
        $transform = function($letters) {
            $letter = array_shift($letters); 
            return '_' . strtolower($letter);
        };
                    

        $array = array();
        foreach ($iterator as $key => $value) {

            if (true === $normalize) {
                $key = preg_replace_callback('/([A-Z])/', $transform, $key);
            }

            if (is_string($value)) {
                if ((true === $detectJsonExpr) && (substr($value,0,5) === '%JS%:')) {
                    $value = new Expr(substr($value,5));
                }
                $array[$key] = $value;
                continue;
            }
            
            if (is_scalar($value)) {
                $array[$key] = $value;
                continue;
            }

            if ($value instanceof Traversable) {
                $array[$key] = static::_toArray($value, $recursive, $detectJsonExpr, $normalize);
                continue;
            }

            if (is_array($value)) {
                $array[$key] = static::_toArray($value, $recursive, $detectJsonExpr, $normalize);
                continue;
            }

            $array[$key] = $value;
        }

        return $array;
    }
    
    
    /**
     * Cast to Json
     * optionally replaces string values starting with %JS%: with Zend/Json/Expr objects 
	 * before encoding
	 *
     * @return Json encoded representation
     */
    public function toJson($handleJsExpressions = false)
    {
        if (!$handleJsExpressions) {
            return JSon::encode($this->toArray(true, false), false);                        
        } else {
            return Json::encode($this->toArray(true, true), false, array('enableJsonExprFinder' => true));        
        }                 
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