<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License 
 */

namespace MxcGenerics\Stdlib\Hydrator;

class ClassMethods extends \Zend\Stdlib\Hydrator\ClassMethods
{
    /**
     * Default parameter value changed to false
     * 
     * @see Zend\Stdlib\Hydrator\ClassMethods
     * @param bool|array $underscoreSeparatedKeys
     */
    public function __construct($underscoreSeparatedKeys = false)
    {
        parent::__construct($underscoreSeparatedKeys);
    }

}
