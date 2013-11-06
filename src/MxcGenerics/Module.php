<?php

namespace MxcGenerics;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements ConfigProviderInterface, AutoloaderProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig() 
    {
		return array (
	 		'Zend\Loader\StandardAutoloader' => array (
			    'namespaces' => array (
				    __NAMESPACE__ => __DIR__, 
				) 
			) 			
		);
	}

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Zend\Session\SessionManager' => 'MxcGenerics\Session\SessionManagerFactory',
            )
        );
    }
}

