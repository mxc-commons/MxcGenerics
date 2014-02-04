<?php

namespace MxcGenerics\Stdlib;

use MxcGenerics\Exception;
use Zend\Stdlib\ArrayUtils;

/** 
 * Generic Options expects
 * 
 * array(
 *     'options' => array(
 *         'set1' => array (
 *             'option1' => value,
 *             ...
 *         ),
 *         'set2' => array (
 *             'option1' => value,
 *             ...
 *         ),
 *         ...
 *     ),
 *     'defaults' => array(
 *         'option1' => value
 *         ... 
 *     )
 *
 */

class GenericOptions extends GenericRegistry {
    
    public function __construct(array $options, $name = 'defaults') {
        $this->loadOptions($options, $name);
    }

    /**
     * Recursively merge optionset $name to default settings
     * 
     * @param array $options
     * @param string $name
     * @throws Exception\InvalidArgumentException
     */
    protected function loadOptions($options, $name) {
        // load default options first
        $opt = isset($options['defaults']) ? $options['defaults'] : array();
		if (is_string($name)) {
            if ($name != 'defaults') {
                if (!isset($options['options'][$name])) {
                    throw new Exception\InvalidArgumentException(
                    	sprintf('Options not found: %s',  $name));
                }
                $optionSet = $options['options'][$name];
                $opt = ArrayUtils::merge($opt, $optionSet);
            }
		}
        $this->setProperties($opt);
    }
}