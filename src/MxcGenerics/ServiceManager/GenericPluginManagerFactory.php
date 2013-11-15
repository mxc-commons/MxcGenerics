<?php

namespace MxcGenerics\ServiceManager;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class GenericPluginManagerFactory implements FactoryInterface {

    const PLUGIN_MANAGER_CLASS = 'GenericPluginManager';
    
    protected $setup;
    
    /* 
	 * @see \Zend\ServiceManager\FactoryInterface::createService()
	 */
	public function createService(ServiceLocatorInterface $serviceLocator) {
        $pluginManagerClass = static::PLUGIN_MANAGER_CLASS;
        /* @var $plugins \Zend\ServiceManager\AbstractPluginManager */
        $plugins = new $pluginManagerClass;
        $plugins->setServiceLocator($serviceLocator);
	    $plugins->setup($this->setup);
	    return $plugins;
	}
} 