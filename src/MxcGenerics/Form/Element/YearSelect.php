<?php
/**
 * This one was for learning reasons, will be deprecated soon
 * 
 * @author frank.hein
 *
 */

namespace MxcGenerics\Form\Element;

use Traversable;
use Zend\Form\Element\Select;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\Explode as ExplodeValidator;
use Zend\Validator\InArray as InArrayValidator;

class YearSelect extends Select implements InputProviderInterface
{
    /**
     * @param  array $options
     * @return YearSelect
     */
    public function setValueOptions(array $options)
    {
        //-- setup year selection
        $base = array_key_exists('base', $options) ? $options['base'] : date('Y');
        $decr = array_key_exists('decr', $options) ? $options['decr'] : 0;
        $incr = array_key_exists('incr', $options) ? $options['incr'] : 0;
        $from = $base-$decr;
        $to =   $base+$incr;
        
        for ($i = $from; $i < $to; $i ++) {
            $yearOptions[$i] = $i;
        }
        
        $this->valueOptions = $yearOptions;

        // Update InArrayValidator validator haystack
        if (null !== $this->validator) {
            if ($this->validator instanceof InArrayValidator) {
                $validator = $this->validator;
            }
            if ($this->validator instanceof ExplodeValidator
                && $this->validator->getValidator() instanceof InArrayValidator
            ) {
                $validator = $this->validator->getValidator();
            }
            if (!empty($validator)) {
                $validator->setHaystack($this->getValueOptionsValues());
            }
        }

        return $this;
    }
    
}
