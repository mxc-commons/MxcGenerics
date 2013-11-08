<?php

namespace MxcGenerics\ServiceManager;

use Zend\ServiceManager\AbstractPluginManager;
use MxcGenerics\ServiceManager\Plugin\AbstractPlugin;
use MxcGenerics\Exception;
use MxcGenerics\Stdlib\GenericRegistry;
use MxcGenerics\Stdlib\GenericOptions;

class GenericPluginManager extends AbstractPluginManager {

    protected $setup;
    protected $pluginConfigurationsKey;
    protected $pluginConfigurations = null;
    protected $pluginContext = null;
    
    public function setup($setup) {
        if (is_string($setup)) {
            $config = $this->getServiceLocator()->get('Configuration');
            $setup = isset($config[$setup]) ? $config[$setup] : array();
            $this->setup = new GenericRegistry($setup);
            return;
        }
        if (is_array($setup)) {
            $this->setup = new GenericRegistry($setup);
            return;
        }
        if ($setup instanceof GenericRegistry) {
            $this->setup = $setup;
            return;
        }
        $this->setup = new GenericRegistry();
    }
        
	public function validatePlugin($plugin) {
        if ($plugin instanceof AbstractPlugin) return;
 
        throw new Exception\RuntimeException(sprintf( 
            'Plugin of type %s is invalid; must be instance of MxcGenerics\ServiceManager\Plugin\AbstractPlugin',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
	}

	/**
	 * @param $name string | array | GenericRegistry
     * @param $options string | array | GenericRegistry
     * 
     * @return $instance 
     */
    public function get($name, $options = null)
    {
        if (is_array($name)) $name = new GenericRegistry($name);
        if ($name instanceof GenericRegistry) {
            $options = $options ? $options : $name->getOptions();
            $name = $name->getName();
        } 
        if (!$name) return null;
        
        $instance = parent::get($name, $name, false);
        //-- to make sure that new options get applied if provided
        if ($options) $instance->init($options);
        return $instance;
    }
    
    public function getPluginConfigurations() {
        if (!$this->pluginConfigurations) {
            $config = $this->getServiceLocator()->get('Configuration');
            $setup = $this->getSetup();
            if (!$setup) {
                throw new Exception\InvalidArgumentException(sprintf(
                    '%s: GenericPluginManager setup not provided.',
                    get_class($this)
                ));
            }
            $key = $setup->getPluginOptions();
            if (!$key) {
                throw new Exception\InvalidArgumentException(sprintf(
                    '%s: No configuration key for plugin options provided.',
                    get_class($this)
                ));
            }
            $this->pluginConfigurations = isset($config[$key]) ? $config[$key] : array();
        }
        return $this->pluginConfigurations;
    }

    public function getPluginOptionSet($type) {
        $pluginConfigurations = $this->getPluginConfigurations();
        return isset($pluginConfigurations[$type]) ? $pluginConfigurations[$type] : array();
    }
    
    public function getPluginOptions($type, $name = 'defaults') {
        $pluginConfigurations = $this->getPluginConfigurations();
        return new GenericOptions($pluginConfigurations[$type],$name);
    }

    public function getPluginContext() {
        if (!$this->pluginContext) {
            $this->pluginContext = new GenericRegistry();
        }
        return $this->pluginContext;
    }
    
    protected function getSetup() {
        return $this->setup;
    }
}