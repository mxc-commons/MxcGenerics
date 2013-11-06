<?php

namespace MxcGenerics\Stdlib;

use MxcGenerics\Exception;

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
    
    protected function loadOptions($options, $name) {
        // load default options first
        $this->setProperties(isset($options['defaults']) ? $options['defaults'] : array());
        if ($name === 'defaults') return;
        //--- silently ignore non strings
        if (is_string($name)) {
            if (!isset($options['options'][$name]))
                throw new Exception\InvalidArgumentException(
                	sprintf('Options not found: %s',  $name));
            // override/extend with option set    
            $this->setProperties($options['options'][$name]);
        }
    }
}