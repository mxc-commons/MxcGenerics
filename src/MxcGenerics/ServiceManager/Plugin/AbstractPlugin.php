<?php

namespace MxcGenerics\ServiceManager\Plugin;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use MxcGenerics\Exception;

abstract class AbstractPlugin 
    implements ServiceLocatorAwareInterface {

    protected $options;
    protected $type; 
    protected $serviceLocator;
    
    public function __construct($type = null) {
        if ($type && !is_string($type)) {
            throw new Exception\InvalidArgumentException(
	           sprintf('%s __construct: type of string expected.',get_class($this))
            );
        }
        $this->type = $type;
    }
    
    public function init($options = null) {
        $this->setOptions($options);       
    }
    
	protected function getServiceManager() {
		return $this->getServiceLocator()->getServiceLocator();
	}
	
	public function getServiceLocator() {
	    return $this->serviceLocator;
	}

	public function setServiceLocator(ServiceLocatorInterface $sl) {
		$this->serviceLocator = $sl;
	}

	protected function getType() {
	    return $this->type;
	}
	
	/**
	 * @return the $options
	 */
	public function getOptions() {
	    if (!$this->options) {
	        $this->options = $this->setupOptions(null);
	    }
		return $this->options;
	} 
	
	protected function getActualOptions($params, $options = null) {
	    $options = $options ? $this->setupOptions($options) : $this->getOptions();
	    if ($params) $options->setProperties($params);
	    return $options;
	}
    
    protected function get($name, $options = null) {
        return $this->getServiceLocator()->get($name, $options);        
    }
	
    protected function getPluginOptions($name = 'defaults') {
        return $this->getServiceLocator()->getPluginOptions($this->getType(), $name);
    }
    
    protected function setupOptions($options) {
        $o = $this->getPluginOptions($options);
        // common default option ResetOptionAfterRun
        if(!$o->getResetOptionsAfterRun()) {
            $o->setResetOptionsAfterRun(false);
        }       
        return $o;
    }
    
    public function setOptions($options) {
        $this->options = $this->setupOptions($options);
    }

    public function resetOptions() {
        $this->options = null;
    }
    
    /**
     * Translate text with object contextual data.
     *
     * @param string $text  The text to translate
     * @return string
     */
    public function translateVars($text)
    {
        return strtr($text, $this->getVars());
    }

    /**
     * Get translate variables. This function is called by translateVars() to
     * translate text and should be overriden in the subclass to match the
     * class requirement.
     *
     * @return array.
     */
    protected function getVars()
    {
      return array();
    }
}