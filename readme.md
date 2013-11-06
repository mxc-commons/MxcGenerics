MxcGenerics
===============
Version 0.1.0 created by Frank Hein and the mxc-commons team.

MxcGenerics is part of the [maxence openBeee initiative](http://www.maxence.de/mxcweb/index.php/themen/open-business/)
by [maxence business consulting gmbh, Germany](http://www.maxence.de). 

Introduction
------------

MxcGenerics provides generic classes and assets we use in development. Other modules from maxence rely on
MxcGenerics.

MxcGenerics is part of the [maxence openBeee initiative](http://www.maxence.de/mxcweb/index.php/themen/open-business/)
by [maxence business consulting gmbh, Germany](http://www.maxence.de). 


Features / Goals
----------------

Main design goal of MxcGenerics is to encapsulate often used generic functionality. 

Requirements
------------

* [Zend Framework 2](https://github.com/zendframework/zf2) (latest master)

Installation
------------

### Main Setup

#### By cloning project

1. Clone this project into your `./vendor/` directory.

#### With composer

1. Add this project in your composer.json:

    ```json
    "require": {
        "mxc-commons/mxc-generics": "dev-master"
    }
    ```

2. Now tell composer to download MxcGenerics by running the command:

    ```bash
    $ php composer.phar update
    ```

#### Post installation

1. Enabling it in your `application.config.php`file.

    ```php
    <?php
    return array(
        'modules' => array(
            // ...
            'MxcGenerics',
        ),
        // ...
    );
    ``` 

Package Contents
----------------

### Directory: Form

##### EventProviderForm

Class derived from Zend\Form\Form enabled to trigger events.

##### EventForm

Class derived from EventProviderForm which issues EVENT_PRE_BIND, EVENT_PRE_PREPARE and EVENT_PRE_VALIDATE.

### Directory: Session

##### SessionManagerFactory

Class which creates a Zend\Session\SessionManager. Configuration options can be supplied through the `'session'`
config key.

### Directory Stdlib

##### GenericRegistry

GenericRegistry is a class comparable to Zend\Stdlib\AbstractOptions. It utilizes magic function to emulate
a setter and getter for each value. Internally data is stored in an associative array.

    $registry = new GenericRegistry();
	$registry->setKneel(72);
    $registry->setPray(42);

	echo $registry->getKneel();
	echo $registry->getPray();

	output: 72 42

##### GenericOptions

GenericOptions is a class derived from GenericRegistry. On construction it expects an array structured like

    $myOptions => array(
       'options' => array(
           'set1' => array(
               'value1' => 10,
               'value2' => 20,
				...
			),
			'set2' => array(
				'value1' => 32,
			),
		),
		'defaults' => array(
			'value1' => 1,
			'value2' => 2,
		),
	);


On construction GenericOptions get initialized with the values from the default section. If an (optional)
option set is specified, these option set will be applied afterwards and extends/overrides the default options.

     $options = new GenericOptions($myOptions); 
	 echo $options->getValue1();	//-- output 1
	 echo $options->getValue2();	//-- output 2   

     $options = new GenericOptions($myOptions, 'set1'); 
	 echo $options->getValue1();	//-- output 10
	 echo $options->getValue2();	//-- output 20   

     $options = new GenericOptions($myOptions, 'set2'); 
	 echo $options->getValue1();	//-- output 32
	 echo $options->getValue2();	//-- output 2   

### Directory Stdlib\Hydrator

##### ClassMethods

Hydrator derived from Zend\Stdlib\Hydrator\ClassMethods. Overrides the `__construct()` parameter's default value for behaviour compatibility reasons regarding other hydrators.

### Directory ServiceManager

##### GenericPluginManager

Class derived from Zend\ServiceManager\AbstractPluginManager. Associates Plugins with a set of GenericOptions.

     'my_plugin_options' => array(
         'plugin1' => array(
			 //-- see GenericOptions above
		 ),
	     'plugin2' => array(
			//-- see GenericOptions above
		 )
	 ),
	 'my_plugins' => array(
		'invobables' => array(
			'plugin1' => 'My\Namespace\MyClass1',
			'plugin2' => 'My\Namespace\MyClass2',
		),
	 ),

##### GenericPluginManagerFactory

Class derived from Zend\Mvc\Service\AbstractPluginManagerFactory. Applies setup options to the GenericPluginManager.

If you derive a plugin manager from GenericPluginManager and register it in the app's onInit you
would implement the factory for your class like this:
	
	class FirewallManagerFactory extends GenericPluginManagerFactory {
	
	    const PLUGIN_MANAGER_CLASS = 'MxcFirewall\FirewallManager\FirewallManager';    
	    
	    /**
		 * @see \Zend\ServiceManager\FactoryInterface::createService()
		 */
		public function createService(ServiceLocatorInterface $serviceLocator) {
		    $config = $serviceLocator->get('Configuration');
		    $this->setup = array(
		       'plugin_options'    => 'firewall-options',
	        );
		    $plugins = parent::createService($serviceLocator);
		    
		    return $plugins;    
		}
	}	 

If you want to create a plugin manager without global registration, you would create a factory
like this (ListenerManager is the class derived from GenericPluginManager in this example):

	class ListenerManagerFactory implements FactoryInterface {
	
	    /* 
		 * @see \Zend\ServiceManager\FactoryInterface::createService()
		 */
		public function createService(ServiceLocatorInterface $serviceLocator) {
		    $config = $serviceLocator->get('Configuration');
		    $config = isset($config['firewall-listeners']) ? $config['firewall-listeners'] : null;
		    $config = new Config($config);
		    $plugins = new ListenerManager($config); 
		    $plugins->setup(array('plugin_options' => 'firewall-listener-options'));
		    return $plugins;
		}
	}

Credits
-------

We saw something like `EventProviderForm` in many modules.  

License
-------

MxcGenerics is released under the New BSD License. See `license.txt`. 