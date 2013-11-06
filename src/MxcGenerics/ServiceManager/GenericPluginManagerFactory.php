<?php

namespace MxcGenerics\ServiceManager;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Service\AbstractPluginManagerFactory;

class GenericPluginManagerFactory extends AbstractPluginManagerFactory {

    const PLUGIN_MANAGER_CLASS = 'GenericPluginManager';
    
    protected $setup;
    
    /* 
	 * @see \Zend\ServiceManager\FactoryInterface::createService()
	 */
	public function createService(ServiceLocatorInterface $serviceLocator) {
	    $plugins = parent::createService($serviceLocator);
	    $plugins->setup($this->setup);
	    return $plugins;
	}
} 